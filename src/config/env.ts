import dotenv from "dotenv";
import { z } from "zod";

dotenv.config();

const envSchema = z.object({
  NODE_ENV: z.enum(["development", "test", "production"]).default("development"),
  PORT: z.coerce.number().default(4000),
  DB_HOST: z.string().default("127.0.0.1"),
  DB_PORT: z.coerce.number().default(5432),
  DB_USER: z.string().default("postgres"),
  DB_PASSWORD: z.string().default("postgres"),
  DB_NAME: z.string().default("shcso"),
  JWT_SECRET: z.string().min(16),
  JWT_EXPIRES_IN: z.string().default("8h")
});

const parsedEnv = envSchema.safeParse(process.env);

if (!parsedEnv.success) {
  console.error("Variables de entorno invalidas:", parsedEnv.error.flatten().fieldErrors);
  throw new Error("No fue posible iniciar el servidor por configuracion invalida.");
}

export const env = parsedEnv.data;

