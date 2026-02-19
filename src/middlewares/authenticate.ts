import { NextFunction, Request, Response } from "express";
import jwt from "jsonwebtoken";
import { env } from "../config/env";
import { HttpError } from "../shared/http-error";

type JwtPayload = {
  sub: string;
  roles: string[];
};

export const authenticate = (req: Request, _res: Response, next: NextFunction): void => {
  const rawHeader = req.headers.authorization;
  if (!rawHeader) {
    throw new HttpError(401, "Falta el token de autorizacion.");
  }

  const [type, token] = rawHeader.split(" ");
  if (type !== "Bearer" || !token) {
    throw new HttpError(401, "Formato de token invalido.");
  }

  try {
    const payload = jwt.verify(token, env.JWT_SECRET) as JwtPayload;
    req.auth = {
      userId: payload.sub,
      roles: payload.roles
    };
    next();
  } catch {
    throw new HttpError(401, "Token invalido o expirado.");
  }
};

