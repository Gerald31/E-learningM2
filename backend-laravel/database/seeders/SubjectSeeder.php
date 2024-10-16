<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SubjectSeeder extends Seeder
{

    const FRS = 'Français';
    const HG_EMC = 'Histoire-Géographie-Enseignement moral et civique';
    const ENGLAIS = 'Anglais';
    const ALLEMAND = 'Allemand';
    const ESPAGNOL = 'Espagnol';
    const ITALIEN = 'Italien';
    const AUTRES_LANGUE = 'Autre langue vivante';
    const MATH = 'Mathématiques';
    const SVT = 'Sciences de la Vie et de la Terre';
    const TECHNOLOGIE = 'Technologie';
    const PC = 'Physique-Chimie';
    const LANGUE_CULTURE = 'Langue et Culture européenne';
    const LANGUE_ANTIQUITE = 'Langue et Culture de l\'Antiquité';
    const LANGUE_REGIONAL = 'Langue et Culture régionale';
    const FRENCH_HG = 'Français-Histoire-Géographie';
    const EMC = 'Enseignement moral et civique';
    const MATH_PC = 'Mathématiques-Physique-Chimie';
    const ARTS_ARTISTIQUE = 'Arts appliqués et culture artistique';
    const EDUCATION_MUSICAL = 'Éducation musicale';
    const HG = 'Histoire-Géographie';
    const SCIENCES_NUMERIQUE = 'Sciences numériques et technologie';
    const LATIN = 'Latin (Langue et Culture de l\'Antiquité)';
    const GREC = 'Grec (Langue et Culture de l\'Antiquité)';
    const LANGUE_SIGNES = 'Langue des signes française';
    const ECOLOGIE_ARGONOMIE = 'Écologie-Agronomie-Territoires-Développement durable';
    const MANAGEMENT_GESTION = 'Management et Gestion';
    const SANTE_SOCIAL = 'Santé et Social';
    const BIO_TECHNOLOGIE = 'Biotechnologies';
    const SCIENCES_LABORATOIRES = 'Sciences et Laboratoires';
    const SCIENCES_INGENIEURS = 'Sciences de l\'Ingénieur';
    const CREATION_INNOVATION_TECH = 'Créations et innovations technologiques';
    const CREATION_DESIGN = 'Créations et culture: design';
    const PRATIQUES_SOCIALES = 'Pratiques sociales et culturelles';
    const PRATIQUES_PROFESSIONNELES = 'Pratiques professionnelles';
    const CULTURE_DANSE = 'Culture et pratique de la danse';
    const CULTURE_MUSIQUE = 'Culture et pratique de la musique';
    const CULTURE_THEATRE = 'Culture et pratique du théâtre';
    const PREVENTION_SANTE = 'Prévention-Santé-Environnement';
    const ENSEIGNEMENT_PROFESSIONNEL = 'Enseignement professionnel';
    const ECO_GEST = 'Économie-Gestion';
    const ECO_DROIT = 'Économie-Droit';
    const FRS_HG_EMC = 'Français-Histoire-Géographie-Enseignement moral et civique';
    const ENSEIGNEGMENT_SCIENTIFIQUE = 'Enseignement scientifique';
    const ARTS = 'Arts';
    const BIO_ECO = 'Biologie-Écologie';
    const HG_GEOPOLITIQUE = 'Histoire-Géographie-Géopolitique-Sciences Politiques';
    const HUMANITES_LITTERATURE = 'Humanités, Littérature et Philosophie';
    const LANGUES_LITTERATURE = 'Langues, Littératures, Cultures étrangères et régionales';
    const LITTERATURE_LANGUE_ANTIQUITE = 'Littératures, Langues et Cultures de l\'Antiquité';
    const NUMERIQUE_SCIENCES_INFO = 'Numérique et Sciences Informatiques';
    const SCIENCES_ECO_SOCIAL = 'Sciences Économiques et Sociales';
    const HIPPOLOGIE_EQUITATION = 'Hippologie et équitation';
    const PHILOSOPHIE = 'Philosophie';
    const MANAGEMENT_SCIENCES_GESTION = 'Management, Sciences de gestion et Numérique';
    const GEST_FINANCE = 'Gestion et Finance';
    const MARKETING = 'Mercatique (Marketing)';
    const RH_COMMUNICATION = 'Ressources humaines et Communication';
    const SYSTEME_INFO_GEST = 'Systèmes d\'Information de Gestion';
    const SCIENCES_GEST_NUM = 'Sciences de Gestion et Numérique';
    const MANAGEMENT = 'Management';
    const DROIT_ECO = 'Droit et Économie';
    const PC_SANTE = 'Physique-Chimie pour la Santé';
    const BIO_PHYSIOPATHOLOGIE = 'Biologie et Physiopathologie humaines';
    const SCIENCE_TECH_SANITAIRE_SOCIALES = 'Sciences et Techniques Sanitaires et Sociales';
    const INNOVATION_TECHNOLOGIE = 'Innovation technologique';
    const I2D = 'Ingénierie et Développement Durable (I2D)';
    const PC_MATH = 'Physique-Chimie et Mathématiques';
    const BIOCHIMIE_BIOLOGIE = 'Biochimie-Biologie';
    const SCIENCES_PC = 'Sciences Physiques et Chimiques en laboratoire';
    const OUTILS_LANGUAGES_NUM = 'Outils et Language numériques';
    const DESIGN_METIERS_ARTS = 'Design et métiers d\'art';
    const SCIENCES = 'Sciences';
    const ECO_GEST_HOTELERIE = 'Économie et gestion hôtelière';
    const SCIENCES_TECH_SERVICES = 'Sciences et Technologies des services';
    const SCIENCES_TECH_CULINAIRES = 'Sciences et Technologies culinaires';
    const ECO_DROIT_ENVIRONNEMENT = 'Économie, droit et environnement du spectacle vivant';
    const CULTURE_SCIENCES_CHORE = 'Culture et sciences chorégraphiques, musicales ou théâtrales';
    const PRATIQUE_CHORE = 'Pratique chorégraphique, musicale ou théâtrale';
    const MATH_COMPLEMENT = 'Mathématiques complémentaires';
    const MATH_EXPERT = 'Mathématiques expertes';
    const DROITS_GRANDS_ENJEUX = 'Droits et grands enjeux du monde contemporain';
    const CHIMIE_BIO_PHYSIOPATHOLOGIE = 'Chimie, Biologie et physiopathologie humaines';
    const INGENIERIE_I2D = 'Ingénierie, Innovation et Développement durable';
    const ARCHI_CONSTRUCTION = 'Architecture et Construction';
    const ENERGIE_ENVIRONNEMENT = 'Énergies et Environnement';
    const INNOVATION_TECHNOLOGIE_ECO_CONCEPTION = 'Innovation technologique et Éco-conception';
    const SCIENCES_INFORMATION_NUMERIQUE = 'Systèmes d\'information et numériques';
    const BIOCHIMIE_BIOLOGIE_BIOTECHNOLOGIE = 'Biochimie-Biologie-Biotechnologie';
    const ANALYSE_METHODE_DESIGN = 'Analyse et Méthode en design';
    const CONCEPTION_CREATION_DESIGN = 'Conception et création en design et métiers d\'art';


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Subject::truncate();
        Schema::enableForeignKeyConstraints();

        $subject = [
            // Seconde Générale et Technologie
            ['subject_name' => self::FRS, 'class_level_id' => 1],
            ['subject_name' => self::ESPAGNOL, 'class_level_id' => 1],
            ['subject_name' => self::ALLEMAND, 'class_level_id' => 1],
            ['subject_name' => self::ITALIEN, 'class_level_id' => 1],
            ['subject_name' => self::AUTRES_LANGUE, 'class_level_id' => 1],
            ['subject_name' => self::MATH, 'class_level_id' => 1],
            ['subject_name' => self::SVT, 'class_level_id' => 1],
            ['subject_name' => self::EMC, 'class_level_id' => 1],
            ['subject_name' => self::SCIENCES_NUMERIQUE, 'class_level_id' => 1],
            ['subject_name' => self::LATIN, 'class_level_id' => 1],
            ['subject_name' => self::GREC, 'class_level_id' => 1],
            ['subject_name' => self::LANGUE_SIGNES, 'class_level_id' => 1],
            ['subject_name' => self::ECOLOGIE_ARGONOMIE, 'class_level_id' => 1],
            ['subject_name' => self::MANAGEMENT_GESTION, 'class_level_id' => 1],
            ['subject_name' => self::SANTE_SOCIAL, 'class_level_id' => 1],
            ['subject_name' => self::BIO_TECHNOLOGIE, 'class_level_id' => 1],
            ['subject_name' => self::SCIENCES_LABORATOIRES, 'class_level_id' => 1],
            ['subject_name' => self::SCIENCES_INGENIEURS,'class_level_id' => 1],
            ['subject_name' => self::CREATION_INNOVATION_TECH,'class_level_id' => 1],
            ['subject_name' => self::CREATION_DESIGN,'class_level_id' => 1],
            ['subject_name' => self::PRATIQUES_SOCIALES,'class_level_id' => 1],
            ['subject_name' => self::PRATIQUES_PROFESSIONNELES,'class_level_id' => 1],
            ['subject_name' => self::CULTURE_DANSE,'class_level_id' => 1],
            ['subject_name' => self::CULTURE_MUSIQUE,'class_level_id' => 1],
            ['subject_name' => self::CULTURE_THEATRE,'class_level_id' => 1],
            // Seconde Profesionnelle
            ['subject_name' => self::PREVENTION_SANTE,'class_level_id' => 2],
            ['subject_name' => self::ENSEIGNEMENT_PROFESSIONNEL,'class_level_id' => 2],
            ['subject_name' => self::ECO_GEST,'class_level_id' => 2],
            ['subject_name' => self::ECO_DROIT,'class_level_id' => 2],
            ['subject_name' => self::FRS_HG_EMC,'class_level_id' => 2],
            ['subject_name' => self::MATH,'class_level_id' => 2],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 2],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 2],
            ['subject_name' => self::ITALIEN,'class_level_id' => 2],
            ['subject_name' => self::ALLEMAND,'class_level_id' => 2],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 2],
            ['subject_name' => self::PC,'class_level_id' => 2],
            ['subject_name' => self::ARTS_ARTISTIQUE,'class_level_id' => 2],
            //Premiere général
            ['subject_name' => self::FRS,'class_level_id' => 3],
            ['subject_name' => self::HG,'class_level_id' => 3],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 3],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 3],
            ['subject_name' => self::ALLEMAND,'class_level_id' => 3],
            ['subject_name' => self::ITALIEN,'class_level_id' => 3],
            ['subject_name' => self::ENSEIGNEGMENT_SCIENTIFIQUE,'class_level_id' => 3],
            ['subject_name' => self::EMC,'class_level_id' => 3],
            ['subject_name' => self::ARTS,'class_level_id' => 3],
            ['subject_name' => self::BIO_ECO,'class_level_id' => 3],
            ['subject_name' => self::HG_GEOPOLITIQUE,'class_level_id' => 3],
            ['subject_name' => self::HUMANITES_LITTERATURE,'class_level_id' => 3],
            ['subject_name' => self::LANGUES_LITTERATURE,'class_level_id' => 3],
            ['subject_name' => self::LITTERATURE_LANGUE_ANTIQUITE,'class_level_id' => 3],
            ['subject_name' => self::MATH,'class_level_id' => 3],
            ['subject_name' => self::NUMERIQUE_SCIENCES_INFO,'class_level_id' => 3],
            ['subject_name' => self::PC,'class_level_id' => 3],
            ['subject_name' => self::SVT,'class_level_id' => 3],
            ['subject_name' => self::SCIENCES_INGENIEURS,'class_level_id' => 3],
            ['subject_name' => self::SCIENCES_ECO_SOCIAL,'class_level_id' => 3],
            ['subject_name' => self::LATIN,'class_level_id' => 3],
            ['subject_name' => self::GREC,'class_level_id' => 3],
            ['subject_name' => self::LANGUE_SIGNES,'class_level_id' => 3],
            ['subject_name' => self::HIPPOLOGIE_EQUITATION,'class_level_id' => 3],
            ['subject_name' => self::PRATIQUES_SOCIALES,'class_level_id' => 3],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 3],
            // Premiere Technologie
            ['subject_name' => self::FRS,'class_level_id' => 4],
            ['subject_name' => self::HG,'class_level_id' => 4],
            ['subject_name' => self::EMC,'class_level_id' => 4],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 4],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 4],
            ['subject_name' => self::ALLEMAND,'class_level_id' => 4],
            ['subject_name' => self::ITALIEN,'class_level_id' => 4],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 4],
            ['subject_name' => self::MATH,'class_level_id' => 4],
            ['subject_name' => self::SCIENCES_GEST_NUM,'class_level_id' => 4],
            ['subject_name' => self::MANAGEMENT,'class_level_id' => 4],
            ['subject_name' => self::DROIT_ECO,'class_level_id' => 4],
            ['subject_name' => self::ARTS,'class_level_id' => 4],
            ['subject_name' => self::LANGUE_SIGNES,'class_level_id' => 4],
            ['subject_name' => self::PC_SANTE,'class_level_id' => 4],
            ['subject_name' => self::BIO_PHYSIOPATHOLOGIE,'class_level_id' => 4],
            ['subject_name' => self::INNOVATION_TECHNOLOGIE,'class_level_id' => 4],
            ['subject_name' => self::I2D,'class_level_id' => 4],
            ['subject_name' => self::PC_MATH,'class_level_id' => 4],
            ['subject_name' => self::BIOCHIMIE_BIOLOGIE,'class_level_id' => 4],
            ['subject_name' => self::BIO_TECHNOLOGIE,'class_level_id' => 4],
            ['subject_name' => self::SCIENCES_PC,'class_level_id' => 4],
            ['subject_name' => self::PC,'class_level_id' => 4],
            ['subject_name' => self::OUTILS_LANGUAGES_NUM,'class_level_id' => 4],
            ['subject_name' => self::DESIGN_METIERS_ARTS,'class_level_id' => 4],
            ['subject_name' => self::SCIENCES,'class_level_id' => 4],
            ['subject_name' => self::ECO_GEST_HOTELERIE,'class_level_id' => 4],
            ['subject_name' => self::SCIENCES_TECH_CULINAIRES,'class_level_id' => 4],
            ['subject_name' => self::ECO_DROIT_ENVIRONNEMENT,'class_level_id' => 4],
            ['subject_name' => self::SCIENCES_TECH_SERVICES,'class_level_id' => 4],
            ['subject_name' => self::CULTURE_SCIENCES_CHORE,'class_level_id' => 4],
            ['subject_name' => self::PRATIQUE_CHORE,'class_level_id' => 4],
            // Premiere Professionnelle
            ['subject_name' => self::ENSEIGNEMENT_PROFESSIONNEL,'class_level_id' => 5],
            ['subject_name' => self::PREVENTION_SANTE,'class_level_id' => 5],
            ['subject_name' => self::ECO_GEST,'class_level_id' => 5],
            ['subject_name' => self::ECO_DROIT,'class_level_id' => 5],
            ['subject_name' => self::FRS_HG_EMC,'class_level_id' => 5],
            ['subject_name' => self::MATH,'class_level_id' => 5],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 5],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 5],
            ['subject_name' => self::ITALIEN,'class_level_id' => 5],
            ['subject_name' => self::ALLEMAND,'class_level_id' => 5],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 5],
            ['subject_name' => self::PC,'class_level_id' => 5],
            ['subject_name' => self::ARTS_ARTISTIQUE,'class_level_id' => 5],
            // Tale Générale
            ['subject_name' => self::ALLEMAND,'class_level_id' => 6],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 6],
            ['subject_name' => self::ARTS,'class_level_id' => 6],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 6],
            ['subject_name' => self::BIO_ECO,'class_level_id' => 6],
            ['subject_name' => self::DROITS_GRANDS_ENJEUX,'class_level_id' => 6],
            ['subject_name' => self::EMC,'class_level_id' => 6],
            ['subject_name' => self::ENSEIGNEGMENT_SCIENTIFIQUE,'class_level_id' => 6],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 6],
            ['subject_name' => self::GREC,'class_level_id' => 6],
            ['subject_name' => self::HIPPOLOGIE_EQUITATION,'class_level_id' => 6],
            ['subject_name' => self::HG,'class_level_id' => 6],
            ['subject_name' => self::HG_GEOPOLITIQUE,'class_level_id' => 6],
            ['subject_name' => self::HUMANITES_LITTERATURE,'class_level_id' => 6],
            ['subject_name' => self::ITALIEN,'class_level_id' => 6],
            ['subject_name' => self::LANGUE_SIGNES,'class_level_id' => 6],
            ['subject_name' => self::LANGUES_LITTERATURE,'class_level_id' => 6],
            ['subject_name' => self::LATIN,'class_level_id' => 6],
            ['subject_name' => self::LITTERATURE_LANGUE_ANTIQUITE,'class_level_id' => 6],
            ['subject_name' => self::MATH,'class_level_id' => 6],
            ['subject_name' => self::MATH_EXPERT,'class_level_id' => 6],
            ['subject_name' => self::MATH_COMPLEMENT,'class_level_id' => 6],
            ['subject_name' => self::NUMERIQUE_SCIENCES_INFO,'class_level_id' => 6],
            ['subject_name' => self::PHILOSOPHIE,'class_level_id' => 6],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 6],
            ['subject_name' => self::PC,'class_level_id' => 6],
            ['subject_name' => self::PRATIQUES_SOCIALES,'class_level_id' => 6],
            ['subject_name' => self::SCIENCES_INGENIEURS,'class_level_id' => 6],
            ['subject_name' => self::SVT,'class_level_id' => 6],
            ['subject_name' => self::SCIENCES_ECO_SOCIAL,'class_level_id' => 6],
            // Tale Technologie
            ['subject_name' => self::ALLEMAND,'class_level_id' => 7],
            ['subject_name' => self::ANALYSE_METHODE_DESIGN,'class_level_id' => 7],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 7],
            ['subject_name' => self::ARCHI_CONSTRUCTION,'class_level_id' => 7],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 7],
            ['subject_name' => self::BIOCHIMIE_BIOLOGIE_BIOTECHNOLOGIE,'class_level_id' => 7],
            ['subject_name' => self::CHIMIE_BIO_PHYSIOPATHOLOGIE,'class_level_id' => 7],
            ['subject_name' => self::CONCEPTION_CREATION_DESIGN,'class_level_id' => 7],
            ['subject_name' => self::CULTURE_SCIENCES_CHORE,'class_level_id' => 7],
            ['subject_name' => self::DROIT_ECO,'class_level_id' => 7],
            ['subject_name' => self::ECO_GEST_HOTELERIE,'class_level_id' => 7],
            ['subject_name' => self::ENERGIE_ENVIRONNEMENT,'class_level_id' => 7],
            ['subject_name' => self::EMC,'class_level_id' => 7],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 7],
            ['subject_name' => self::GEST_FINANCE,'class_level_id' => 7],
            ['subject_name' => self::HG,'class_level_id' => 7],
            ['subject_name' => self::INGENIERIE_I2D,'class_level_id' => 7],
            ['subject_name' => self::INNOVATION_TECHNOLOGIE_ECO_CONCEPTION,'class_level_id' => 7],
            ['subject_name' => self::ITALIEN,'class_level_id' => 7],
            ['subject_name' => self::LANGUE_SIGNES,'class_level_id' => 7],
            ['subject_name' => self::MANAGEMENT_SCIENCES_GESTION,'class_level_id' => 7],
            ['subject_name' => self::MATH,'class_level_id' => 7],
            ['subject_name' => self::MARKETING,'class_level_id' => 7],
            ['subject_name' => self::PHILOSOPHIE,'class_level_id' => 7],
            ['subject_name' => self::PC_MATH,'class_level_id' => 7],
            ['subject_name' => self::PRATIQUE_CHORE,'class_level_id' => 7],
            ['subject_name' => self::RH_COMMUNICATION,'class_level_id' => 7],
            ['subject_name' => self::SCIENCE_TECH_SANITAIRE_SOCIALES,'class_level_id' => 7],
            ['subject_name' => self::SCIENCES_PC,'class_level_id' => 7],
            ['subject_name' => self::SYSTEME_INFO_GEST,'class_level_id' => 7],
            ['subject_name' => self::SCIENCES_INFORMATION_NUMERIQUE,'class_level_id' => 7],
            // Tale Professionnelle
            ['subject_name' => self::ALLEMAND,'class_level_id' => 8],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 8],
            ['subject_name' => self::ARTS_ARTISTIQUE,'class_level_id' => 8],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 8],
            ['subject_name' => self::ECO_GEST,'class_level_id' => 8],
            ['subject_name' => self::ECO_DROIT,'class_level_id' => 8],
            ['subject_name' => self::ENSEIGNEMENT_PROFESSIONNEL,'class_level_id' => 8],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 8],
            ['subject_name' => self::FRS_HG_EMC,'class_level_id' => 8],
            ['subject_name' => self::MATH,'class_level_id' => 8],
            ['subject_name' => self::PC,'class_level_id' => 8],
            ['subject_name' => self::ITALIEN,'class_level_id' => 8],
            ['subject_name' => self::PREVENTION_SANTE,'class_level_id' => 8],
            // CAP
            ['subject_name' => self::ALLEMAND,'class_level_id' => 9],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 9],
            ['subject_name' => self::ARTS_ARTISTIQUE,'class_level_id' => 9],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 9],
            ['subject_name' => self::EMC,'class_level_id' => 9],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 9],
            ['subject_name' => self::FRENCH_HG,'class_level_id' => 9],
            ['subject_name' => self::MATH_PC,'class_level_id' => 9],
            // 6 ieme
            ['subject_name' => self::ALLEMAND,'class_level_id' => 10],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 10],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 10],
            ['subject_name' => self::EDUCATION_MUSICAL,'class_level_id' => 10],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 10],
            ['subject_name' => self::FRS,'class_level_id' => 10],
            ['subject_name' => self::HG_EMC,'class_level_id' => 10],
            ['subject_name' => self::ITALIEN,'class_level_id' => 10],
            ['subject_name' => self::MATH,'class_level_id' => 10],
            ['subject_name' => self::PC,'class_level_id' => 10],
            ['subject_name' => self::SVT,'class_level_id' => 10],
            ['subject_name' => self::TECHNOLOGIE,'class_level_id' => 10],
            // 5eme
            ['subject_name' => self::ALLEMAND,'class_level_id' => 11],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 11],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 11],
            ['subject_name' => self::EDUCATION_MUSICAL,'class_level_id' => 11],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 11],
            ['subject_name' => self::FRS,'class_level_id' => 11],
            ['subject_name' => self::HG_EMC,'class_level_id' => 11],
            ['subject_name' => self::ITALIEN,'class_level_id' => 11],
            ['subject_name' => self::LANGUE_ANTIQUITE,'class_level_id' => 11],
            ['subject_name' => self::LANGUE_CULTURE,'class_level_id' => 11],
            ['subject_name' => self::MATH,'class_level_id' => 11],
            ['subject_name' => self::PC,'class_level_id' => 11],
            ['subject_name' => self::SVT,'class_level_id' => 11],
            ['subject_name' => self::TECHNOLOGIE,'class_level_id' => 11],
            // 4 eme
            ['subject_name' => self::ALLEMAND,'class_level_id' => 12],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 12],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 12],
            ['subject_name' => self::EDUCATION_MUSICAL,'class_level_id' => 12],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 12],
            ['subject_name' => self::FRS,'class_level_id' => 12],
            ['subject_name' => self::HG_EMC,'class_level_id' => 12],
            ['subject_name' => self::ITALIEN,'class_level_id' => 12],
            ['subject_name' => self::LANGUE_ANTIQUITE,'class_level_id' => 12],
            ['subject_name' => self::LANGUE_CULTURE,'class_level_id' => 12],
            ['subject_name' => self::LANGUE_REGIONAL,'class_level_id' => 12],
            ['subject_name' => self::MATH,'class_level_id' => 12],
            ['subject_name' => self::PC,'class_level_id' => 12],
            ['subject_name' => self::SVT,'class_level_id' => 12],
            // 3 eme
            ['subject_name' => self::ALLEMAND,'class_level_id' => 13],
            ['subject_name' => self::ENGLAIS,'class_level_id' => 13],
            ['subject_name' => self::AUTRES_LANGUE,'class_level_id' => 13],
            ['subject_name' => self::EDUCATION_MUSICAL,'class_level_id' => 13],
            ['subject_name' => self::ESPAGNOL,'class_level_id' => 13],
            ['subject_name' => self::FRS,'class_level_id' => 13],
            ['subject_name' => self::HG_EMC,'class_level_id' => 13],
            ['subject_name' => self::ITALIEN,'class_level_id' => 13],
            ['subject_name' => self::LANGUE_ANTIQUITE,'class_level_id' => 13],
            ['subject_name' => self::LANGUE_CULTURE,'class_level_id' => 13],
            ['subject_name' => self::LANGUE_REGIONAL,'class_level_id' => 13],
            ['subject_name' => self::MATH,'class_level_id' => 13],
            ['subject_name' => self::PC,'class_level_id' => 13],
        ];

        Subject::insert($subject);
    }
}
