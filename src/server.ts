import { app } from "./app";
import { db } from "./config/db";
import { env } from "./config/env";

const start = async () => {
  await db.query("SELECT 1");

  app.listen(env.PORT, () => {
    console.log(`SHCSO API escuchando en http://localhost:${env.PORT}`);
  });
};

start().catch((error) => {
  console.error("No fue posible iniciar SHCSO API:", error.message);
  process.exit(1);
});

