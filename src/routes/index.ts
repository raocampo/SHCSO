import { Router } from "express";
import { login, me, registerAdmin } from "../modules/auth/auth.controller";
import {
  createCompany,
  createJobPosition,
  listCompanies,
  listJobPositions
} from "../modules/catalog/catalog.controller";
import {
  createCertificateFromEvaluation,
  getCertificateById
} from "../modules/certificates/certificates.controller";
import { createEvaluation, getEvaluationById } from "../modules/evaluations/evaluations.controller";
import { createWorker, getWorkerById, listWorkers } from "../modules/workers/workers.controller";
import { authenticate } from "../middlewares/authenticate";
import { authorize } from "../middlewares/authorize";
import { asyncHandler } from "../shared/async-handler";

export const apiRouter = Router();

apiRouter.get("/health", (_req, res) => {
  res.status(200).json({
    ok: true,
    message: "API SHCSO activa",
    timestamp: new Date().toISOString()
  });
});

apiRouter.post("/auth/register-admin", asyncHandler(registerAdmin));
apiRouter.post("/auth/login", asyncHandler(login));

apiRouter.use(authenticate);
apiRouter.get("/auth/me", asyncHandler(me));

apiRouter.get("/catalog/companies", asyncHandler(listCompanies));
apiRouter.post("/catalog/companies", authorize(["ADMIN", "RECEPCION"]), asyncHandler(createCompany));
apiRouter.get("/catalog/job-positions", asyncHandler(listJobPositions));
apiRouter.post(
  "/catalog/job-positions",
  authorize(["ADMIN", "RECEPCION"]),
  asyncHandler(createJobPosition)
);

apiRouter.get(
  "/workers",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL", "ENFERMERIA", "RECEPCION", "AUDITOR"]),
  asyncHandler(listWorkers)
);
apiRouter.get(
  "/workers/:workerId",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL", "ENFERMERIA", "RECEPCION", "AUDITOR"]),
  asyncHandler(getWorkerById)
);
apiRouter.post(
  "/workers",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL", "ENFERMERIA", "RECEPCION"]),
  asyncHandler(createWorker)
);

apiRouter.post(
  "/evaluations",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL"]),
  asyncHandler(createEvaluation)
);
apiRouter.get(
  "/evaluations/:evaluationId",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL", "ENFERMERIA", "AUDITOR"]),
  asyncHandler(getEvaluationById)
);

apiRouter.post(
  "/certificates/from-evaluation/:evaluationId",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL"]),
  asyncHandler(createCertificateFromEvaluation)
);
apiRouter.get(
  "/certificates/:certificateId",
  authorize(["ADMIN", "MEDICO_OCUPACIONAL", "ENFERMERIA", "AUDITOR"]),
  asyncHandler(getCertificateById)
);

