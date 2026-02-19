import { Pool } from "pg";
import { env } from "./env";

export const db = new Pool({
  host: env.DB_HOST,
  port: env.DB_PORT,
  user: env.DB_USER,
  password: env.DB_PASSWORD,
  database: env.DB_NAME,
  max: 15,
  idleTimeoutMillis: 30_000
});

db.on("error", (error: Error) => {
  console.error("Error inesperado en el pool de PostgreSQL:", error.message);
});
