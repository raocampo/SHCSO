<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando cuadro básico de medicamentos MSP/CONASA Ecuador...');

        foreach ($this->getMedications() as $med) {
            Medication::updateOrCreate(
                ['generic_name' => $med[0], 'concentration' => $med[2]],
                [
                    'generic_name'        => $med[0],
                    'commercial_name'     => $med[1] ?? null,
                    'concentration'       => $med[2] ?? null,
                    'pharmaceutical_form' => $med[3] ?? null,
                    'therapeutic_group'   => $med[4] ?? null,
                    'via_administracion'  => $med[5] ?? null,
                    'controlled'          => $med[6] ?? false,
                    'active'              => true,
                ]
            );
        }

        $count = Medication::count();
        $this->command->info("✅ Cuadro básico cargado: {$count} medicamentos.");
    }

    /** [generic_name, commercial_name, concentration, pharmaceutical_form, therapeutic_group, via, controlled] */
    private function getMedications(): array
    {
        return [
            // ── ANALGÉSICOS / ANTIINFLAMATORIOS ──────────────────────────────
            ['Paracetamol', 'Tylenol', '500 mg', 'Tableta', 'Analgésico/Antipirético', 'Oral', false],
            ['Paracetamol', 'Tylenol', '1 g', 'Tableta', 'Analgésico/Antipirético', 'Oral', false],
            ['Paracetamol', 'Tylenol', '125 mg/5 ml', 'Solución pediátrica', 'Analgésico/Antipirético', 'Oral', false],
            ['Paracetamol', 'Tylenol IV', '10 mg/ml', 'Solución inyectable', 'Analgésico/Antipirético', 'IV', false],
            ['Ibuprofeno', 'Advil', '400 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Ibuprofeno', 'Advil', '600 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Ibuprofeno', 'Advil', '200 mg/5 ml', 'Suspensión pediátrica', 'AINE', 'Oral', false],
            ['Diclofenaco', 'Voltaren', '50 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Diclofenaco', 'Voltaren', '75 mg/3 ml', 'Solución inyectable', 'AINE', 'IM', false],
            ['Diclofenaco', 'Voltaren gel', '10 mg/g', 'Gel tópico', 'AINE', 'Tópico', false],
            ['Naproxeno', 'Naprosyn', '250 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Naproxeno', 'Naprosyn', '500 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Ketorolaco', 'Toradol', '30 mg/ml', 'Solución inyectable', 'AINE', 'IM/IV', false],
            ['Ketorolaco', 'Toradol', '10 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Meloxicam', 'Mobic', '7.5 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Meloxicam', 'Mobic', '15 mg', 'Tableta', 'AINE', 'Oral', false],
            ['Celecoxib', 'Celebrex', '200 mg', 'Cápsula', 'AINE COX-2', 'Oral', false],
            ['Tramadol', 'Tramal', '50 mg', 'Cápsula', 'Analgésico opioide', 'Oral', true],
            ['Tramadol', 'Tramal', '100 mg/2 ml', 'Solución inyectable', 'Analgésico opioide', 'IM/IV', true],
            ['Morfina', null, '10 mg/ml', 'Solución inyectable', 'Analgésico opioide', 'SC/IM/IV', true],
            ['Codeína + Paracetamol', null, '30 mg/500 mg', 'Tableta', 'Analgésico opioide', 'Oral', true],
            // ── ANTIBIÓTICOS ─────────────────────────────────────────────────
            ['Amoxicilina', 'Amoxil', '500 mg', 'Cápsula', 'Antibiótico penicilínico', 'Oral', false],
            ['Amoxicilina', 'Amoxil', '250 mg/5 ml', 'Polvo para suspensión', 'Antibiótico penicilínico', 'Oral', false],
            ['Amoxicilina + Ácido clavulánico', 'Augmentin', '875 mg/125 mg', 'Tableta', 'Antibiótico penicilínico', 'Oral', false],
            ['Amoxicilina + Ácido clavulánico', 'Augmentin', '500 mg/125 mg', 'Tableta', 'Antibiótico penicilínico', 'Oral', false],
            ['Ampicilina', null, '1 g', 'Polvo inyectable', 'Antibiótico penicilínico', 'IM/IV', false],
            ['Azitromicina', 'Zithromax', '500 mg', 'Tableta', 'Macrólido', 'Oral', false],
            ['Azitromicina', 'Zithromax', '250 mg', 'Tableta', 'Macrólido', 'Oral', false],
            ['Claritromicina', 'Biaxin', '500 mg', 'Tableta', 'Macrólido', 'Oral', false],
            ['Eritromicina', null, '500 mg', 'Tableta', 'Macrólido', 'Oral', false],
            ['Ciprofloxacino', 'Cipro', '500 mg', 'Tableta', 'Fluoroquinolona', 'Oral', false],
            ['Ciprofloxacino', 'Cipro', '200 mg/100 ml', 'Solución inyectable', 'Fluoroquinolona', 'IV', false],
            ['Levofloxacino', 'Levaquin', '500 mg', 'Tableta', 'Fluoroquinolona', 'Oral', false],
            ['Norfloxacino', 'Noroxin', '400 mg', 'Tableta', 'Fluoroquinolona', 'Oral', false],
            ['Cefalexina', 'Keflex', '500 mg', 'Cápsula', 'Cefalosporina 1G', 'Oral', false],
            ['Cefadroxilo', null, '500 mg', 'Cápsula', 'Cefalosporina 1G', 'Oral', false],
            ['Cefazolina', null, '1 g', 'Polvo inyectable', 'Cefalosporina 1G', 'IM/IV', false],
            ['Cefuroxima', 'Zinnat', '500 mg', 'Tableta', 'Cefalosporina 2G', 'Oral', false],
            ['Ceftriaxona', 'Rocephin', '1 g', 'Polvo inyectable', 'Cefalosporina 3G', 'IM/IV', false],
            ['Ceftriaxona', 'Rocephin', '500 mg', 'Polvo inyectable', 'Cefalosporina 3G', 'IM/IV', false],
            ['Metronidazol', 'Flagyl', '500 mg', 'Tableta', 'Nitroimidazol', 'Oral', false],
            ['Metronidazol', 'Flagyl', '500 mg/100 ml', 'Solución inyectable', 'Nitroimidazol', 'IV', false],
            ['Doxiciclina', 'Vibramycin', '100 mg', 'Cápsula', 'Tetraciclina', 'Oral', false],
            ['Trimetoprim + Sulfametoxazol', 'Bactrim', '160/800 mg', 'Tableta', 'Sulfonamida', 'Oral', false],
            ['Clindamicina', 'Dalacin', '300 mg', 'Cápsula', 'Lincosamida', 'Oral', false],
            ['Clindamicina', 'Dalacin', '600 mg/4 ml', 'Solución inyectable', 'Lincosamida', 'IM/IV', false],
            ['Gentamicina', null, '80 mg/2 ml', 'Solución inyectable', 'Aminoglucósido', 'IM', false],
            ['Nitrofurantoína', 'Macrobid', '100 mg', 'Cápsula', 'Antibiótico urinario', 'Oral', false],
            ['Fosfomicina', 'Monurol', '3 g', 'Polvo oral sobre', 'Antibiótico urinario', 'Oral', false],
            // ── CARDIOVASCULAR ───────────────────────────────────────────────
            ['Enalapril', 'Vasotec', '5 mg', 'Tableta', 'IECA', 'Oral', false],
            ['Enalapril', 'Vasotec', '10 mg', 'Tableta', 'IECA', 'Oral', false],
            ['Enalapril', 'Vasotec', '20 mg', 'Tableta', 'IECA', 'Oral', false],
            ['Losartán', 'Cozaar', '50 mg', 'Tableta', 'ARA II', 'Oral', false],
            ['Losartán', 'Cozaar', '100 mg', 'Tableta', 'ARA II', 'Oral', false],
            ['Valsartán', 'Diovan', '80 mg', 'Tableta', 'ARA II', 'Oral', false],
            ['Amlodipino', 'Norvasc', '5 mg', 'Tableta', 'Calcioantagonista', 'Oral', false],
            ['Amlodipino', 'Norvasc', '10 mg', 'Tableta', 'Calcioantagonista', 'Oral', false],
            ['Nifedipino', 'Adalat', '30 mg', 'Tableta retard', 'Calcioantagonista', 'Oral', false],
            ['Metoprolol', 'Lopressor', '50 mg', 'Tableta', 'Betabloqueador', 'Oral', false],
            ['Metoprolol', 'Lopressor', '100 mg', 'Tableta', 'Betabloqueador', 'Oral', false],
            ['Atenolol', 'Tenormin', '50 mg', 'Tableta', 'Betabloqueador', 'Oral', false],
            ['Carvedilol', 'Coreg', '12.5 mg', 'Tableta', 'Betabloqueador', 'Oral', false],
            ['Furosemida', 'Lasix', '40 mg', 'Tableta', 'Diurético', 'Oral', false],
            ['Furosemida', 'Lasix', '20 mg/2 ml', 'Solución inyectable', 'Diurético', 'IM/IV', false],
            ['Hidroclorotiazida', null, '25 mg', 'Tableta', 'Diurético tiazídico', 'Oral', false],
            ['Espironolactona', 'Aldactone', '25 mg', 'Tableta', 'Diurético ahorrador K', 'Oral', false],
            ['Atorvastatina', 'Lipitor', '10 mg', 'Tableta', 'Estatina', 'Oral', false],
            ['Atorvastatina', 'Lipitor', '20 mg', 'Tableta', 'Estatina', 'Oral', false],
            ['Atorvastatina', 'Lipitor', '40 mg', 'Tableta', 'Estatina', 'Oral', false],
            ['Simvastatina', 'Zocor', '20 mg', 'Tableta', 'Estatina', 'Oral', false],
            ['Rosuvastatina', 'Crestor', '10 mg', 'Tableta', 'Estatina', 'Oral', false],
            ['Aspirina', 'Bayer', '100 mg', 'Tableta', 'Antiagregante/AINE', 'Oral', false],
            ['Clopidogrel', 'Plavix', '75 mg', 'Tableta', 'Antiagregante', 'Oral', false],
            ['Nitroglicerina', null, '0.5 mg', 'Tableta sublingual', 'Antianginal', 'Sublingual', false],
            ['Digoxina', 'Lanoxin', '0.25 mg', 'Tableta', 'Glucósido cardíaco', 'Oral', false],
            // ── RESPIRATORIO ─────────────────────────────────────────────────
            ['Salbutamol', 'Ventolin', '100 mcg/dosis', 'Inhalador MDI', 'Broncodilatador β2', 'Inhalada', false],
            ['Salbutamol', 'Ventolin', '2.5 mg/2.5 ml', 'Solución nebulización', 'Broncodilatador β2', 'Nebulización', false],
            ['Ipratropio', 'Atrovent', '20 mcg/dosis', 'Inhalador MDI', 'Anticolinérgico bronquial', 'Inhalada', false],
            ['Beclometasona', 'QVAR', '250 mcg/dosis', 'Inhalador MDI', 'Corticoide inhalado', 'Inhalada', false],
            ['Budesonida', 'Pulmicort', '200 mcg/dosis', 'Inhalador MDI', 'Corticoide inhalado', 'Inhalada', false],
            ['Fluticasona + Salmeterol', 'Seretide', '250/25 mcg', 'Inhalador MDI', 'Corticoide+LABA', 'Inhalada', false],
            ['Ambroxol', 'Mucosolvan', '30 mg', 'Tableta', 'Mucolítico', 'Oral', false],
            ['Ambroxol', 'Mucosolvan', '15 mg/5 ml', 'Jarabe', 'Mucolítico', 'Oral', false],
            ['Acetilcisteína', 'Fluimucil', '600 mg', 'Sobre efervescente', 'Mucolítico', 'Oral', false],
            ['Dextrometorfano', null, '15 mg/5 ml', 'Jarabe', 'Antitusígeno', 'Oral', false],
            ['Prednisona', 'Deltasone', '5 mg', 'Tableta', 'Corticoide sistémico', 'Oral', false],
            ['Prednisona', 'Deltasone', '20 mg', 'Tableta', 'Corticoide sistémico', 'Oral', false],
            ['Metilprednisolona', 'Depo-Medrol', '500 mg', 'Polvo inyectable', 'Corticoide sistémico', 'IV', false],
            ['Dexametasona', 'Decadron', '4 mg/2 ml', 'Solución inyectable', 'Corticoide sistémico', 'IM/IV', false],
            // ── GASTROINTESTINAL ─────────────────────────────────────────────
            ['Omeprazol', 'Prilosec', '20 mg', 'Cápsula', 'IBP', 'Oral', false],
            ['Omeprazol', 'Prilosec', '40 mg', 'Polvo inyectable', 'IBP', 'IV', false],
            ['Pantoprazol', 'Protonix', '40 mg', 'Tableta', 'IBP', 'Oral', false],
            ['Esomeprazol', 'Nexium', '40 mg', 'Cápsula', 'IBP', 'Oral', false],
            ['Ranitidina', 'Zantac', '150 mg', 'Tableta', 'Anti-H2', 'Oral', false],
            ['Metoclopramida', 'Reglan', '10 mg/2 ml', 'Solución inyectable', 'Procinético/Antiemético', 'IM/IV', false],
            ['Metoclopramida', 'Reglan', '10 mg', 'Tableta', 'Procinético/Antiemético', 'Oral', false],
            ['Ondansetrón', 'Zofran', '4 mg', 'Tableta', 'Antiemético 5-HT3', 'Oral', false],
            ['Ondansetrón', 'Zofran', '4 mg/2 ml', 'Solución inyectable', 'Antiemético 5-HT3', 'IM/IV', false],
            ['Dimenhidrinato', 'Dramamine', '50 mg', 'Tableta', 'Antiemético/Antivértigo', 'Oral', false],
            ['Bisacodilo', 'Dulcolax', '5 mg', 'Tableta', 'Laxante', 'Oral', false],
            ['Loperamida', 'Imodium', '2 mg', 'Cápsula', 'Antidiarreico', 'Oral', false],
            ['Hidróxido de aluminio + Magnesio', 'Maalox', '400/400 mg', 'Tableta masticable', 'Antiácido', 'Oral', false],
            ['Simeticona', 'Gas-X', '80 mg', 'Tableta masticable', 'Carminativo', 'Oral', false],
            // ── ENDOCRINOLÓGICO / METABÓLICO ─────────────────────────────────
            ['Metformina', 'Glucophage', '500 mg', 'Tableta', 'Antidiabético biguanida', 'Oral', false],
            ['Metformina', 'Glucophage', '850 mg', 'Tableta', 'Antidiabético biguanida', 'Oral', false],
            ['Glibenclamida', 'Micronase', '5 mg', 'Tableta', 'Antidiabético sulfonilurea', 'Oral', false],
            ['Insulina NPH', 'Humulin N', '100 UI/ml', 'Solución inyectable', 'Insulina acción intermedia', 'SC', false],
            ['Insulina regular', 'Humulin R', '100 UI/ml', 'Solución inyectable', 'Insulina acción corta', 'SC/IV', false],
            ['Levotiroxina', 'Synthroid', '50 mcg', 'Tableta', 'Hormona tiroidea', 'Oral', false],
            ['Levotiroxina', 'Synthroid', '100 mcg', 'Tableta', 'Hormona tiroidea', 'Oral', false],
            ['Metimazol', 'Tapazole', '5 mg', 'Tableta', 'Antitiroidéo', 'Oral', false],
            ['Calcio + Vitamina D3', null, '500 mg/200 UI', 'Tableta', 'Suplemento mineral', 'Oral', false],
            ['Sulfato ferroso', null, '300 mg', 'Tableta', 'Suplemento hierro', 'Oral', false],
            ['Ácido fólico', null, '5 mg', 'Tableta', 'Vitamina', 'Oral', false],
            // ── DERMATOLÓGICO ────────────────────────────────────────────────
            ['Hidrocortisona', null, '1%', 'Crema tópica', 'Corticoide tópico leve', 'Tópico', false],
            ['Betametasona', 'Diprolene', '0.05%', 'Crema tópica', 'Corticoide tópico potente', 'Tópico', false],
            ['Clotrimazol', 'Lotrimin', '1%', 'Crema tópica', 'Antifúngico tópico', 'Tópico', false],
            ['Miconazol', 'Monistat', '2%', 'Crema tópica', 'Antifúngico tópico', 'Tópico', false],
            ['Fluconazol', 'Diflucan', '150 mg', 'Cápsula', 'Antifúngico oral', 'Oral', false],
            ['Fluconazol', 'Diflucan', '150 mg/150 ml', 'Solución inyectable', 'Antifúngico oral', 'IV', false],
            ['Mupirocina', 'Bactroban', '2%', 'Ungüento tópico', 'Antibiótico tópico', 'Tópico', false],
            ['Loratadina', 'Claritin', '10 mg', 'Tableta', 'Antihistamínico no sedante', 'Oral', false],
            ['Cetirizina', 'Zyrtec', '10 mg', 'Tableta', 'Antihistamínico no sedante', 'Oral', false],
            ['Difenhidramina', 'Benadryl', '50 mg', 'Cápsula', 'Antihistamínico sedante', 'Oral', false],
            ['Clorfeniramina', null, '4 mg', 'Tableta', 'Antihistamínico sedante', 'Oral', false],
            // ── SISTEMA NERVIOSO ─────────────────────────────────────────────
            ['Diazepam', 'Valium', '5 mg', 'Tableta', 'Benzodiacepina ansiolítico', 'Oral', true],
            ['Diazepam', 'Valium', '10 mg/2 ml', 'Solución inyectable', 'Benzodiacepina ansiolítico', 'IM/IV', true],
            ['Alprazolam', 'Xanax', '0.25 mg', 'Tableta', 'Benzodiacepina ansiolítico', 'Oral', true],
            ['Clonazepam', 'Klonopin', '0.5 mg', 'Tableta', 'Benzodiacepina antiepiléptico', 'Oral', true],
            ['Lorazepam', 'Ativan', '2 mg/ml', 'Solución inyectable', 'Benzodiacepina', 'IM/IV', true],
            ['Sertralina', 'Zoloft', '50 mg', 'Tableta', 'Antidepresivo ISRS', 'Oral', false],
            ['Fluoxetina', 'Prozac', '20 mg', 'Cápsula', 'Antidepresivo ISRS', 'Oral', false],
            ['Escitalopram', 'Lexapro', '10 mg', 'Tableta', 'Antidepresivo ISRS', 'Oral', false],
            ['Amitriptilina', 'Elavil', '25 mg', 'Tableta', 'Antidepresivo tricíclico', 'Oral', false],
            ['Carbamazepina', 'Tegretol', '200 mg', 'Tableta', 'Antiepiléptico', 'Oral', false],
            ['Fenitoína', 'Dilantin', '100 mg', 'Cápsula', 'Antiepiléptico', 'Oral', false],
            ['Haloperidol', 'Haldol', '5 mg', 'Tableta', 'Antipsicótico típico', 'Oral', false],
            ['Haloperidol', 'Haldol', '5 mg/ml', 'Solución inyectable', 'Antipsicótico típico', 'IM', false],
            // ── MÚSCULO ESQUELÉTICO ──────────────────────────────────────────
            ['Ciclobenzaprina', 'Flexeril', '10 mg', 'Tableta', 'Relajante muscular', 'Oral', false],
            ['Metocarbamol', 'Robaxin', '750 mg', 'Tableta', 'Relajante muscular', 'Oral', false],
            ['Tizanidina', 'Zanaflex', '4 mg', 'Tableta', 'Relajante muscular', 'Oral', false],
            ['Colchicina', 'Colcrys', '0.5 mg', 'Tableta', 'Antigotoso', 'Oral', false],
            ['Alopurinol', 'Zyloprim', '100 mg', 'Tableta', 'Antigotoso/Uricosúrico', 'Oral', false],
            ['Alopurinol', 'Zyloprim', '300 mg', 'Tableta', 'Antigotoso/Uricosúrico', 'Oral', false],
            ['Glucosamina + Condroitina', null, '500/400 mg', 'Tableta', 'Condroprotector', 'Oral', false],
            // ── OFTALMOLÓGICO / ORL ──────────────────────────────────────────
            ['Ciprofloxacino', 'Ciloxan', '3 mg/ml', 'Colirio', 'Antibiótico ocular', 'Oftálmico', false],
            ['Tobramicina', 'Tobrex', '3 mg/ml', 'Colirio', 'Antibiótico ocular', 'Oftálmico', false],
            ['Prednisolona', 'Pred Forte', '10 mg/ml', 'Colirio', 'Corticoide ocular', 'Oftálmico', false],
            ['Timolol', 'Timoptic', '5 mg/ml', 'Colirio', 'Betabloqueador ocular', 'Oftálmico', false],
            ['Xilometazolina', 'Otrivin', '0.1%', 'Solución nasal', 'Descongestionante', 'Nasal', false],
            ['Beclometasona', 'Beconase', '50 mcg/dosis', 'Spray nasal', 'Corticoide nasal', 'Nasal', false],
            // ── VACUNAS Y BIOLÓGICOS ─────────────────────────────────────────
            ['Vacuna antitetánica', null, '1 dosis/0.5 ml', 'Solución inyectable', 'Vacuna', 'IM', false],
            ['Vacuna anti-hepatitis B', null, '1 dosis/1 ml', 'Solución inyectable', 'Vacuna', 'IM', false],
            ['Vacuna influenza', null, '1 dosis/0.5 ml', 'Solución inyectable', 'Vacuna', 'IM', false],
            // ── SOLUCIONES Y FLUIDOS ─────────────────────────────────────────
            ['Cloruro de sodio', 'NaCl 0.9%', '9 mg/ml', 'Solución inyectable 1L', 'Solución electrolítica', 'IV', false],
            ['Dextrosa', null, '50 mg/ml', 'Solución inyectable 1L', 'Solución glucosada', 'IV', false],
            ['Lactato de Ringer', null, '1 L', 'Solución inyectable', 'Solución electrolítica', 'IV', false],
        ];
    }
}
