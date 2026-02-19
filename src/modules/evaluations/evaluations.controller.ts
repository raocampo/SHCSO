import { Request, Response } from "express";
import { z } from "zod";
import { db } from "../../config/db";
import { writeAuditLog } from "../../shared/audit";
import { HttpError } from "../../shared/http-error";

const diagnosisSchema = z.object({
  code: z.string().min(3).max(12),
  description: z.string().max(400).optional(),
  diagnosis_type: z.enum(["PRE", "DEF"]),
  notes: z.string().max(500).optional()
});

const evaluationSchema = z.object({
  worker_id: z.uuid(),
  evaluation_type: z.enum(["INGRESO", "PERIODICO", "REINTEGRO", "RETIRO"]),
  attention_date: z.iso.date().optional(),
  consultation_reason: z.string().min(5),
  personal_background: z.record(z.string(), z.unknown()).optional(),
  current_problem: z.string().optional(),
  vital_signs: z.record(z.string(), z.unknown()).optional(),
  physical_exam: z.record(z.string(), z.unknown()).optional(),
  risk_factors: z.record(z.string(), z.unknown()).optional(),
  labor_activity_history: z.record(z.string(), z.unknown()).optional(),
  extra_activities: z.record(z.string(), z.unknown()).optional(),
  exam_results: z.record(z.string(), z.unknown()).optional(),
  medical_aptitude: z.enum(["APTO", "APTO_OBSERVACION", "APTO_LIMITACIONES", "NO_APTO"]),
  restrictions: z.string().optional(),
  recommendations: z.string().optional(),
  retirement_notes: z.string().optional(),
  professional_name: z.string().min(3),
  professional_code: z.string().min(3),
  worker_signature_path: z.string().optional(),
  diagnoses: z.array(diagnosisSchema).optional()
});

type DiagnosisPayload = z.infer<typeof diagnosisSchema>;

export const createEvaluation = async (req: Request, res: Response): Promise<void> => {
  if (!req.auth) {
    throw new HttpError(401, "Sesion no valida.");
  }

  const data = evaluationSchema.parse(req.body);

  const workerExists = await db.query("SELECT id FROM workers WHERE id = $1", [data.worker_id]);
  if (workerExists.rowCount === 0) {
    throw new HttpError(404, "Trabajador no encontrado.");
  }

  const result = await db.query(
    `INSERT INTO occupational_evaluations (
      worker_id, evaluator_user_id, evaluation_type, attention_date, consultation_reason,
      personal_background, current_problem, vital_signs, physical_exam, risk_factors,
      labor_activity_history, extra_activities, exam_results, medical_aptitude,
      restrictions, recommendations, retirement_notes, professional_name,
      professional_code, worker_signature_path
    )
    VALUES (
      $1, $2, $3, $4, $5,
      $6::jsonb, $7, $8::jsonb, $9::jsonb, $10::jsonb,
      $11::jsonb, $12::jsonb, $13::jsonb, $14,
      $15, $16, $17, $18,
      $19, $20
    )
    RETURNING *`,
    [
      data.worker_id,
      req.auth.userId,
      data.evaluation_type,
      data.attention_date ?? new Date().toISOString().slice(0, 10),
      data.consultation_reason,
      JSON.stringify(data.personal_background ?? {}),
      data.current_problem ?? null,
      JSON.stringify(data.vital_signs ?? {}),
      JSON.stringify(data.physical_exam ?? {}),
      JSON.stringify(data.risk_factors ?? {}),
      JSON.stringify(data.labor_activity_history ?? {}),
      JSON.stringify(data.extra_activities ?? {}),
      JSON.stringify(data.exam_results ?? {}),
      data.medical_aptitude,
      data.restrictions ?? null,
      data.recommendations ?? null,
      data.retirement_notes ?? null,
      data.professional_name,
      data.professional_code,
      data.worker_signature_path ?? null
    ]
  );

  const evaluation = result.rows[0];

  if (data.diagnoses && data.diagnoses.length > 0) {
    for (const diagnosis of data.diagnoses as DiagnosisPayload[]) {
      await db.query(
        `INSERT INTO diagnosis_catalog (code, description)
         VALUES ($1, $2)
         ON CONFLICT (code) DO NOTHING`,
        [diagnosis.code, diagnosis.description ?? `Diagnostico ${diagnosis.code}`]
      );

      await db.query(
        `INSERT INTO evaluation_diagnoses (evaluation_id, diagnosis_code, diagnosis_type, notes)
         VALUES ($1, $2, $3, $4)`,
        [evaluation.id, diagnosis.code, diagnosis.diagnosis_type, diagnosis.notes ?? null]
      );
    }
  }

  await writeAuditLog({
    userId: req.auth.userId,
    action: "CREATE_EVALUATION",
    entityType: "occupational_evaluation",
    entityId: evaluation.id,
    metadata: {
      worker_id: data.worker_id,
      medical_aptitude: data.medical_aptitude
    }
  });

  res.status(201).json({
    ok: true,
    data: evaluation
  });
};

export const getEvaluationById = async (req: Request, res: Response): Promise<void> => {
  const evaluationId = z.uuid().parse(req.params.evaluationId);

  const evaluationResult = await db.query(
    `SELECT
      e.*,
      w.first_name,
      w.last_name,
      w.document_number
     FROM occupational_evaluations e
     INNER JOIN workers w ON w.id = e.worker_id
     WHERE e.id = $1`,
    [evaluationId]
  );

  if (evaluationResult.rowCount === 0) {
    throw new HttpError(404, "Evaluacion no encontrada.");
  }

  const diagnosisResult = await db.query(
    `SELECT
      d.id,
      d.diagnosis_code,
      c.description AS diagnosis_description,
      d.diagnosis_type,
      d.notes
     FROM evaluation_diagnoses d
     INNER JOIN diagnosis_catalog c ON c.code = d.diagnosis_code
     WHERE d.evaluation_id = $1
     ORDER BY d.id`,
    [evaluationId]
  );

  res.status(200).json({
    ok: true,
    data: {
      ...evaluationResult.rows[0],
      diagnoses: diagnosisResult.rows
    }
  });
};
