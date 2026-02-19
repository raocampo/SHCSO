import { NextFunction, Request, Response } from "express";
import { HttpError } from "../shared/http-error";

export const notFoundHandler = (_req: Request, res: Response): void => {
  res.status(404).json({
    ok: false,
    message: "Ruta no encontrada."
  });
};

export const errorHandler = (
  err: unknown,
  _req: Request,
  res: Response,
  _next: NextFunction
): void => {
  if (err instanceof HttpError) {
    res.status(err.statusCode).json({
      ok: false,
      message: err.message,
      details: err.details
    });
    return;
  }

  const unexpected = err as Error;
  console.error("Error no controlado:", unexpected.message);
  res.status(500).json({
    ok: false,
    message: "Error interno del servidor."
  });
};

