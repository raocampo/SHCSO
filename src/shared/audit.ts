import { db } from "../config/db";

type AuditPayload = {
  userId?: string;
  action: string;
  entityType: string;
  entityId: string;
  metadata?: Record<string, unknown>;
};

export const writeAuditLog = async (payload: AuditPayload): Promise<void> => {
  await db.query(
    `INSERT INTO audit_logs (user_id, action, entity_type, entity_id, metadata)
     VALUES ($1, $2, $3, $4, $5::jsonb)`,
    [
      payload.userId ?? null,
      payload.action,
      payload.entityType,
      payload.entityId,
      JSON.stringify(payload.metadata ?? {})
    ]
  );
};

