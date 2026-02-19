import { Request, Response } from "express";
import { z } from "zod";
import { db } from "../../config/db";
import { writeAuditLog } from "../../shared/audit";
import { HttpError } from "../../shared/http-error";

const certificateSchema = z.object({
  issue_date: z.iso.date().optional(),
  observations: z.string().optional(),
  recommendations: z.string().optional(),
  worker_signature_path: z.string().optional(),
  pdf_path: z.string().optional(),
  qr_code_data: z.string().optional()
});

const buildCertificateCode = () => {
  const now = new Date();
  const yyyy = now.getUTCFullYear();
  const mm = String(now.getUTCMonth() + 1).padStart(2, "0");
  const dd = String(now.getUTCDate()).padStart(2, "0");
  const serial = Math.floor(Math.random() * 900000 + 100000);
  return `CERT-${yyyy}${mm}${dd}-${serial}`;
};

export const createCertificateFromEvaluation = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const evaluationId = z.uuid().parse(req.params.evaluationId);
  const data = certificateSchema.parse(req.body ?? {});

  const evaluationResult = await db.query(
    `SELECT
      id,
      worker_id,
      medical_aptitude,
      professional_name,
      professional_code,
      recommendations
     FROM occupational_evaluations
     WHERE id = $1`,
    [evaluationId]
  );

  if (evaluationResult.rowCount === 0) {
    throw new HttpError(404, "Evaluacion no encontrada para generar certificado.");
  }

  const evaluation = evaluationResult.rows[0] as {
    id: string;
    worker_id: string;
    medical_aptitude: "APTO" | "APTO_OBSERVACION" | "APTO_LIMITACIONES" | "NO_APTO";
    professional_name: string;
    professional_code: string;
    recommendations: string | null;
  };

  const certificateCode = buildCertificateCode();

  const insertResult = await db.query(
    `INSERT INTO medical_certificates (
      certificate_code, evaluation_id, worker_id, issue_date, medical_aptitude,
      observations, recommendations, professional_name, professional_code,
      worker_signature_path, pdf_path, qr_code_data, created_by
    )
    VALUES (
      $1, $2, $3, $4, $5,
      $6, $7, $8, $9,
      $10, $11, $12, $13
    )
    RETURNING *`,
    [
      certificateCode,
      evaluation.id,
      evaluation.worker_id,
      data.issue_date ?? new Date().toISOString().slice(0, 10),
      evaluation.medical_aptitude,
      data.observations ?? null,
      data.recommendations ?? evaluation.recommendations ?? null,
      evaluation.professional_name,
      evaluation.professional_code,
      data.worker_signature_path ?? null,
      data.pdf_path ?? null,
      data.qr_code_data ?? null,
      req.auth.userId
    ]
  );

  await writeAuditLog({
    userId: req.auth.userId,
    action: "CREATE_CERTIFICATE",
    entityType: "medical_certificate",
    entityId: insertResult.rows[0].id,
    metadata: { evaluation_id: evaluation.id }
  });

  res.status(201).json({
    ok: true,
    data: insertResult.rows[0]
  });
};

export const getCertificateById = async (req: Request, res: Response): Promise<void> => {
  const certificateId = z.uuid().parse(req.params.certificateId);

  const result = await db.query(
    `SELECT
      c.*,
      w.first_name,
      w.last_name,
      w.document_number
     FROM medical_certificates c
     INNER JOIN workers w ON w.id = c.worker_id
     WHERE c.id = $1`,
    [certificateId]
  );

  if (result.rowCount === 0) {
    throw new HttpError(404, "Certificado no encontrado.");
  }

  res.status(200).json({
    ok: true,
    data: result.rows[0]
  });
};

