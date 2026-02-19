import { NextFunction, Request, Response } from "express";
import { HttpError } from "../shared/http-error";

export const authorize = (allowedRoles: string[]) => {
  return (req: Request, _res: Response, next: NextFunction): void => {
    if (!req.auth) {
      throw new HttpError(401, "Debe iniciar sesion.");
    }

    const hasRole = req.auth.roles.some((role) => allowedRoles.includes(role));
    if (!hasRole) {
      throw new HttpError(403, "No tiene permisos para esta accion.");
    }

    next();
  };
};

