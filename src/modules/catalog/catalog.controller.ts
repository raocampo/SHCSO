import { Request, Response } from "express";
import { z } from "zod";
import { db } from "../../config/db";
import { writeAuditLog } from "../../shared/audit";
import { HttpError } from "../../shared/http-error";

const companySchema = z.object({
  ruc: z.string().max(13).optional(),
  ciiu: z.string().max(12).optional(),
  business_name: z.string().min(3).max(180),
  work_center: z.string().max(180).optional(),
  address: z.string().max(500).optional()
});

const jobPositionSchema = z.object({
  ciuo_code: z.string().max(12).optional(),
  name: z.string().min(3).max(160),
  description: z.string().max(500).optional()
});

export const createCompany = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const data = companySchema.parse(req.body);

  const result = await db.query(
    `INSERT INTO companies (ruc, ciiu, business_name, work_center, address)
     VALUES ($1, $2, $3, $4, $5)
     RETURNING *`,
    [data.ruc ?? null, data.ciiu ?? null, data.business_name, data.work_center ?? null, data.address ?? null]
  );

  await writeAuditLog({
    userId: req.auth.userId,
    action: "CREATE_COMPANY",
    entityType: "company",
    entityId: String(result.rows[0].id)
  });

  res.status(201).json({
    ok: true,
    data: result.rows[0]
  });
};

export const listCompanies = async (_req: Request, res: Response): Promise<void> => {
  const result = await db.query(`SELECT * FROM companies ORDER BY created_at DESC LIMIT 200`);
  res.status(200).json({ ok: true, data: result.rows });
};

export const createJobPosition = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const data = jobPositionSchema.parse(req.body);

  const result = await db.query(
    `INSERT INTO job_positions (ciuo_code, name, description)
     VALUES ($1, $2, $3)
     RETURNING *`,
    [data.ciuo_code ?? null, data.name, data.description ?? null]
  );

  await writeAuditLog({
    userId: req.auth.userId,
    action: "CREATE_JOB_POSITION",
    entityType: "job_position",
    entityId: String(result.rows[0].id)
  });

  res.status(201).json({
    ok: true,
    data: result.rows[0]
  });
};

export const listJobPositions = async (_req: Request, res: Response): Promise<void> => {
  const result = await db.query(`SELECT * FROM job_positions ORDER BY created_at DESC LIMIT 200`);
  res.status(200).json({ ok: true, data: result.rows });
};

