CREATE EXTENSION IF NOT EXISTS pgcrypto;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sex_enum') THEN
    CREATE TYPE sex_enum AS ENUM ('M', 'F', 'O');
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'evaluation_type_enum') THEN
    CREATE TYPE evaluation_type_enum AS ENUM ('INGRESO', 'PERIODICO', 'REINTEGRO', 'RETIRO');
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'diagnosis_type_enum') THEN
    CREATE TYPE diagnosis_type_enum AS ENUM ('PRE', 'DEF');
  END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'aptitude_enum') THEN
    CREATE TYPE aptitude_enum AS ENUM ('APTO', 'APTO_OBSERVACION', 'APTO_LIMITACIONES', 'NO_APTO');
  END IF;
END $$;

CREATE TABLE IF NOT EXISTS roles (
  id SMALLSERIAL PRIMARY KEY,
  name VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS user_roles (
  user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  role_id SMALLINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
  PRIMARY KEY (user_id, role_id)
);

CREATE TABLE IF NOT EXISTS companies (
  id BIGSERIAL PRIMARY KEY,
  ruc VARCHAR(13) UNIQUE,
  ciiu VARCHAR(12),
  business_name VARCHAR(180) NOT NULL,
  work_center VARCHAR(180),
  address TEXT,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS job_positions (
  id BIGSERIAL PRIMARY KEY,
  ciuo_code VARCHAR(12),
  name VARCHAR(160) NOT NULL,
  description TEXT,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS workers (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  history_number VARCHAR(30) NOT NULL UNIQUE,
  file_number VARCHAR(30) NOT NULL UNIQUE,
  document_type VARCHAR(30) NOT NULL,
  document_number VARCHAR(30) NOT NULL UNIQUE,
  first_name VARCHAR(120) NOT NULL,
  last_name VARCHAR(120) NOT NULL,
  email VARCHAR(160),
  phone VARCHAR(30),
  birth_date DATE NOT NULL,
  sex sex_enum NOT NULL,
  blood_type VARCHAR(10),
  laterality VARCHAR(15),
  is_pregnant BOOLEAN,
  has_disability BOOLEAN,
  catastrophic_disease BOOLEAN,
  is_elderly BOOLEAN,
  company_id BIGINT REFERENCES companies(id) ON DELETE SET NULL,
  job_position_id BIGINT REFERENCES job_positions(id) ON DELETE SET NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS diagnosis_catalog (
  code VARCHAR(12) PRIMARY KEY,
  description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS occupational_evaluations (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  worker_id UUID NOT NULL REFERENCES workers(id) ON DELETE CASCADE,
  evaluator_user_id UUID NOT NULL REFERENCES users(id),
  evaluation_type evaluation_type_enum NOT NULL,
  attention_date DATE NOT NULL DEFAULT CURRENT_DATE,
  consultation_reason TEXT NOT NULL,
  personal_background JSONB NOT NULL DEFAULT '{}'::JSONB,
  current_problem TEXT,
  vital_signs JSONB NOT NULL DEFAULT '{}'::JSONB,
  physical_exam JSONB NOT NULL DEFAULT '{}'::JSONB,
  risk_factors JSONB NOT NULL DEFAULT '{}'::JSONB,
  labor_activity_history JSONB NOT NULL DEFAULT '{}'::JSONB,
  extra_activities JSONB NOT NULL DEFAULT '{}'::JSONB,
  exam_results JSONB NOT NULL DEFAULT '{}'::JSONB,
  medical_aptitude aptitude_enum NOT NULL,
  restrictions TEXT,
  recommendations TEXT,
  retirement_notes TEXT,
  professional_name VARCHAR(150) NOT NULL,
  professional_code VARCHAR(60) NOT NULL,
  worker_signature_path TEXT,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS evaluation_diagnoses (
  id BIGSERIAL PRIMARY KEY,
  evaluation_id UUID NOT NULL REFERENCES occupational_evaluations(id) ON DELETE CASCADE,
  diagnosis_code VARCHAR(12) NOT NULL REFERENCES diagnosis_catalog(code),
  diagnosis_type diagnosis_type_enum NOT NULL,
  notes TEXT
);

CREATE TABLE IF NOT EXISTS evaluation_attachments (
  id BIGSERIAL PRIMARY KEY,
  evaluation_id UUID NOT NULL REFERENCES occupational_evaluations(id) ON DELETE CASCADE,
  file_name VARCHAR(255) NOT NULL,
  file_path TEXT NOT NULL,
  mime_type VARCHAR(100) NOT NULL,
  uploaded_by UUID REFERENCES users(id) ON DELETE SET NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS medical_certificates (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  certificate_code VARCHAR(40) NOT NULL UNIQUE,
  evaluation_id UUID NOT NULL REFERENCES occupational_evaluations(id) ON DELETE CASCADE,
  worker_id UUID NOT NULL REFERENCES workers(id) ON DELETE CASCADE,
  issue_date DATE NOT NULL DEFAULT CURRENT_DATE,
  medical_aptitude aptitude_enum NOT NULL,
  observations TEXT,
  recommendations TEXT,
  professional_name VARCHAR(150) NOT NULL,
  professional_code VARCHAR(60) NOT NULL,
  worker_signature_path TEXT,
  pdf_path TEXT,
  qr_code_data TEXT,
  created_by UUID REFERENCES users(id),
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGSERIAL PRIMARY KEY,
  user_id UUID REFERENCES users(id) ON DELETE SET NULL,
  action VARCHAR(80) NOT NULL,
  entity_type VARCHAR(80) NOT NULL,
  entity_id VARCHAR(80) NOT NULL,
  metadata JSONB NOT NULL DEFAULT '{}'::JSONB,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

INSERT INTO roles(name)
VALUES
  ('ADMIN'),
  ('MEDICO_OCUPACIONAL'),
  ('ENFERMERIA'),
  ('RECEPCION'),
  ('AUDITOR')
ON CONFLICT (name) DO NOTHING;

CREATE INDEX IF NOT EXISTS idx_workers_document_number ON workers(document_number);
CREATE INDEX IF NOT EXISTS idx_workers_company ON workers(company_id);
CREATE INDEX IF NOT EXISTS idx_evaluations_worker ON occupational_evaluations(worker_id);
CREATE INDEX IF NOT EXISTS idx_evaluations_attention_date ON occupational_evaluations(attention_date);
CREATE INDEX IF NOT EXISTS idx_certificates_worker ON medical_certificates(worker_id);
CREATE INDEX IF NOT EXISTS idx_audit_logs_created_at ON audit_logs(created_at);

