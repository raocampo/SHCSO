INSERT INTO companies (ruc, ciiu, business_name, work_center, address)
VALUES ('0999999999001', 'Q8621.01', 'Empresa Demo SHCSO', 'Planta Principal', 'Direccion referencial')
ON CONFLICT (ruc) DO NOTHING;

INSERT INTO job_positions (ciuo_code, name, description)
VALUES
  ('2261', 'Medico Ocupacional', 'Responsable de evaluaciones y certificados ocupacionales'),
  ('3256', 'Enfermeria', 'Apoyo en toma de signos y registro clinico'),
  ('4321', 'Recepcion', 'Agendamiento y registro de pacientes')
ON CONFLICT DO NOTHING;

INSERT INTO diagnosis_catalog (code, description)
VALUES
  ('Z00.0', 'Examen medico general'),
  ('M54.5', 'Lumbalgia'),
  ('H52.4', 'Presbicia')
ON CONFLICT (code) DO NOTHING;

