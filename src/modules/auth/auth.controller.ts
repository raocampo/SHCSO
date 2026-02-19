import bcrypt from "bcryptjs";
import { Request, Response } from "express";
import jwt from "jsonwebtoken";
import { z } from "zod";
import { db } from "../../config/db";
import { env } from "../../config/env";
import { writeAuditLog } from "../../shared/audit";
import { HttpError } from "../../shared/http-error";

const registerSchema = z.object({
  full_name: z.string().min(3),
  email: z.email(),
  password: z.string().min(8)
});

const loginSchema = z.object({
  email: z.email(),
  password: z.string().min(8)
});

export const registerAdmin = async (req: Request, res: Response): Promise<void> => {
  const data = registerSchema.parse(req.body);

  const adminCountResult = await db.query(
    `SELECT COUNT(*)::int AS count
     FROM users u
     INNER JOIN user_roles ur ON ur.user_id = u.id
     INNER JOIN roles r ON r.id = ur.role_id
     WHERE r.name = 'ADMIN'`
  );
  const adminCount = adminCountResult.rows[0].count as number;
  if (adminCount > 0) {
    throw new HttpError(409, "Ya existe un usuario ADMIN. Cree nuevos usuarios desde un endpoint protegido.");
  }

  const passwordHash = await bcrypt.hash(data.password, 12);

  const userInsert = await db.query(
    `INSERT INTO users (full_name, email, password_hash)
     VALUES ($1, $2, $3)
     RETURNING id, full_name, email, created_at`,
    [data.full_name, data.email.toLowerCase(), passwordHash]
  );

  const user = userInsert.rows[0];
  await db.query(
    `INSERT INTO user_roles (user_id, role_id)
     SELECT $1, id FROM roles WHERE name = 'ADMIN'`,
    [user.id]
  );

  await writeAuditLog({
    userId: user.id,
    action: "CREATE_ADMIN",
    entityType: "user",
    entityId: user.id,
    metadata: { email: user.email }
  });

  res.status(201).json({
    ok: true,
    message: "Administrador creado correctamente.",
    data: user
  });
};

export const login = async (req: Request, res: Response): Promise<void> => {
  const data = loginSchema.parse(req.body);

  const result = await db.query(
    `SELECT
      u.id,
      u.full_name,
      u.email,
      u.password_hash,
      u.is_active,
      COALESCE(array_agg(r.name) FILTER (WHERE r.name IS NOT NULL), '{}') AS roles
     FROM users u
     LEFT JOIN user_roles ur ON ur.user_id = u.id
     LEFT JOIN roles r ON r.id = ur.role_id
     WHERE u.email = $1
     GROUP BY u.id`,
    [data.email.toLowerCase()]
  );

  if (result.rowCount === 0) {
    throw new HttpError(401, "Credenciales invalidas.");
  }

  const user = result.rows[0] as {
    id: string;
    full_name: string;
    email: string;
    password_hash: string;
    is_active: boolean;
    roles: string[];
  };

  if (!user.is_active) {
    throw new HttpError(403, "Usuario inactivo.");
  }

  const passwordValid = await bcrypt.compare(data.password, user.password_hash);
  if (!passwordValid) {
    throw new HttpError(401, "Credenciales invalidas.");
  }

  const token = jwt.sign(
    {
      sub: user.id,
      roles: user.roles
    },
    env.JWT_SECRET,
    { expiresIn: env.JWT_EXPIRES_IN as jwt.SignOptions["expiresIn"] }
  );

  await writeAuditLog({
    userId: user.id,
    action: "LOGIN",
    entityType: "auth",
    entityId: user.id
  });

  res.status(200).json({
    ok: true,
    data: {
      token,
      user: {
        id: user.id,
        full_name: user.full_name,
        email: user.email,
        roles: user.roles
      }
    }
  });
};

export const me = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const result = await db.query(
    `SELECT
      u.id,
      u.full_name,
      u.email,
      COALESCE(array_agg(r.name) FILTER (WHERE r.name IS NOT NULL), '{}') AS roles
     FROM users u
     LEFT JOIN user_roles ur ON ur.user_id = u.id
     LEFT JOIN roles r ON r.id = ur.role_id
     WHERE u.id = $1
     GROUP BY u.id`,
    [req.auth.userId]
  );

  if (result.rowCount === 0) {
    throw new HttpError(404, "Usuario no encontrado.");
  }

  res.status(200).json({
    ok: true,
    data: result.rows[0]
  });
};
