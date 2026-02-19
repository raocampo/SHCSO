import { Request, Response } from "express";
import { z } from "zod";
import { db } from "../../config/db";
import { writeAuditLog } from "../../shared/audit";
import { HttpError } from "../../shared/http-error";

const workerSchema = z.object({
  document_type: z.string().min(2).max(30),
  document_number: z.string().min(5).max(30),
  first_name: z.string().min(2).max(120),
  last_name: z.string().min(2).max(120),
  birth_date: z.iso.date(),
  sex: z.enum(["M", "F", "O"]),
  email: z.email().optional(),
  phone: z.string().max(30).optional(),
  blood_type: z.string().max(10).optional(),
  laterality: z.string().max(15).optional(),
  is_pregnant: z.boolean().optional(),
  has_disability: z.boolean().optional(),
  catastrophic_disease: z.boolean().optional(),
  is_elderly: z.boolean().optional(),
  company_id: z.coerce.number().int().optional(),
  job_position_id: z.coerce.number().int().optional()
});

const buildCode = (prefix: string) => `${prefix}-${Date.now().toString().slice(-8)}-${Math.floor(Math.random() * 90 + 10)}`;

export const createWorker = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const data = workerSchema.parse(req.body);

  const historyNumber = buildCode("HC");
  const fileNumber = buildCode("AR");

  const result = await db.query(
    `INSERT INTO workers (
      history_number, file_number, document_type, document_number, first_name, last_name,
      email, phone, birth_date, sex, blood_type, laterality, is_pregnant, has_disability,
      catastrophic_disease, is_elderly, company_id, job_position_id
    )
    VALUES (
      $1, $2, $3, $4, $5, $6,
      $7, $8, $9, $10, $11, $12, $13, $14,
      $15, $16, $17, $18
    )
    RETURNING *`,
    [
      historyNumber,
      fileNumber,
      data.document_type,
      data.document_number,
      data.first_name,
      data.last_name,
      data.email ?? null,
      data.phone ?? null,
      data.birth_date,
      data.sex,
      data.blood_type ?? null,
      data.laterality ?? null,
      data.is_pregnant ?? null,
      data.has_disability ?? null,
      data.catastrophic_disease ?? null,
      data.is_elderly ?? null,
      data.company_id ?? null,
      data.job_position_id ?? null
    ]
  );

  const worker = result.rows[0];

  await writeAuditLog({
    userId: req.auth.userId,
    action: "CREATE_WORKER",
    entityType: "worker",
    entityId: worker.id,
    metadata: { document_number: worker.document_number }
  });

  res.status(201).json({
    ok: true,
    data: worker
  });
};

export const listWorkers = async (req: Request, res: Response): Promise<void> => {
  const q = typeof req.query.q === "string" ? req.query.q.trim() : "";
  const limit = Number.parseInt(String(req.query.limit ?? "20"), 10);
  const safeLimit = Number.isNaN(limit) ? 20 : Math.min(Math.max(limit, 1), 100);

  const result = await db.query(
    `SELECT
      w.*,
      c.business_name,
      jp.name AS job_position_name
     FROM workers w
     LEFT JOIN companies c ON c.id = w.company_id
     LEFT JOIN job_positions jp ON jp.id = w.job_position_id
     WHERE ($1 = '' OR
       w.document_number ILIKE '%' || $1 || '%' OR
       CONCAT(w.first_name, ' ', w.last_name) ILIKE '%' || $1 || '%')
     ORDER BY w.created_at DESC
     LIMIT $2`,
    [q, safeLimit]
  );

  res.status(200).json({
    ok: true,
    data: result.rows
  });
};

export const getWorkerById = async (req: Request, res: Response): Promise<void> => {
  const workerId = z.uuid().parse(req.params.workerId);

  const result = await db.query(
    `SELECT
      w.*,
      c.business_name,
      jp.name AS job_position_name
     FROM workers w
     LEFT JOIN companies c ON c.id = w.company_id
     LEFT JOIN job_positions jp ON jp.id = w.job_position_id
     WHERE w.id = $1`,
    [workerId]
  );

  if (result.rowCount === 0) {
    throw new HttpError(404, "Trabajador no encontrado.");
  }

  res.status(200).json({
    ok: true,
    data: result.rows[0]
  });
};

