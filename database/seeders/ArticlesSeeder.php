<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'kerphilesaint@gmail.com')->first();

        if (!$admin) {
            $this->command->error('Compte admin introuvable. Lancez AdminUserSeeder d\'abord.');
            return;
        }

        if (Post::count() > 0) {
            $this->command->info('Articles déjà présents — ignoré.');
            return;
        }

        // Images de couverture (sources web) : storage/app/public/covers/
        // coupe-monde → Wikimedia (Mbappé, Griezmann, Fekir, Giroud + trophée, CC BY-SA 4.0)
        // sante → Pexels #1640770 | ia → Unsplash | wadagni → Wikimedia skyline Cotonou (CC BY-SA 4.0)
        $articles = [
            [
                'title' => 'Coupe du Monde 2026 : tout ce qu\'il faut savoir',
                'cover_image' => 'covers/coupe-monde-2026.jpg',
                'category' => 'Sport',
                'tags' => 'football, coupe du monde, FIFA, 2026',
                'excerpt' => 'Du 11 juin au 19 juillet 2026, le Mondial réunit pour la première fois 48 équipes au Canada, au Mexique et aux États-Unis.',
                'content' => <<<'TEXT'
La Coupe du Monde de la FIFA 2026 s'annonce comme la plus grande édition de l'histoire du football. Pour la première fois, le tournoi se déroulera dans trois pays — le Canada, le Mexique et les États-Unis — et accueillera 48 sélections nationales, contre 32 auparavant.

Dates et calendrier

Le coup d'envoi est fixé au jeudi 11 juin 2026. Le match d'ouverture opposera le Mexique à l'Afrique du Sud au mythique Stade Azteca de Mexico. La compétition s'achèvera le dimanche 19 juillet 2026 avec la finale au MetLife Stadium, dans la région de New York / New Jersey.

Au total, 104 matchs seront disputés sur environ cinq semaines, avec une phase de groupes du 11 au 28 juin, puis des phases à élimination directe jusqu'à la finale.

Un format inédit

Le Conseil de la FIFA a validé un format à 12 groupes de quatre équipes. Les deux premiers de chaque groupe, ainsi que les huit meilleurs troisièmes, se qualifieront pour les seizièmes de finale. Ce nouveau système offre plus de matchs, plus de suspense et une place accrue aux nations émergentes.

Les villes hôtes

Seize villes accueilleront la compétition :
- Canada : Toronto et Vancouver
- Mexique : Mexico, Guadalajara et Monterrey
- États-Unis : Atlanta, Boston, Dallas, Houston, Kansas City, Los Angeles, Miami, New York/New Jersey, Philadelphie, San Francisco et Seattle

Des enceintes ultramodernes, capables d'accueillir des dizaines de milliers de spectateurs, seront au cœur du spectacle.

Pourquoi ce Mondial est historique

Au-delà du sport, la Coupe du Monde 2026 représente un projet continental unique : trois nations unies pour célébrer le football mondial. Les retombées économiques, touristiques et culturelles s'annoncent considérables pour l'Amérique du Nord.

Côté sportif, des favoris comme le Brésil, l'Argentine, la France ou l'Allemagne seront attendus au tournant, mais le format élargi laisse la porte ouverte aux surprises. Une chose est sûre : en juin 2026, le monde entier aura les yeux rivés sur ce rendez-vous planétaire.
TEXT,
            ],
            [
                'title' => 'Les habitudes santé essentielles en 2026',
                'cover_image' => 'covers/sante-2026.jpg',
                'category' => 'Santé',
                'tags' => 'santé, bien-être, nutrition, sport',
                'excerpt' => 'Sommeil, alimentation, activité physique et équilibre mental : les piliers d\'une vie saine selon les recommandations actuelles.',
                'content' => <<<'TEXT'
Prendre soin de sa santé n'a jamais été aussi important. En 2026, les recommandations des professionnels convergent vers des habitudes simples, accessibles et scientifiquement éprouvées.

1. Prioriser le sommeil

Dormir 7 à 8 heures par nuit reste la base d'une bonne santé. Le sommeil régule l'humeur, renforce le système immunitaire, améliore la concentration et favorise la récupération musculaire. Évitez les écrans une heure avant le coucher et maintenez des horaires réguliers.

2. Bouger chaque jour

La marche rapide, la natation ou simplement 30 minutes d'activité modérée par jour réduisent les risques cardiovasculaires, le stress et le surpoids. L'OMS recommande au moins 150 minutes d'activité physique par semaine.

3. Manger plus de végétaux

Une alimentation riche en fruits, légumes, céréales complètes et protéines maigres protège contre les maladies chroniques. Réduire le sucre ajouté, les boissons sucrées et les aliments ultra-transformés fait une réelle différence sur le long terme.

4. S'hydrater correctement

Boire environ 1,5 à 2 litres d'eau par jour aide le corps à fonctionner optimalement : digestion, circulation, peau et énergie. En climat chaud ou lors d'efforts physiques, les besoins augmentent.

5. Gérer le stress

Méditation, respiration profonde, promenades dans la nature ou quelques minutes de pause sans écran permettent de réduire l'anxiété. La santé mentale est indissociable de la santé physique.

6. Cultiver les liens sociaux

Maintenir des relations de qualité avec sa famille, ses amis ou sa communauté protège contre l'isolement et la dépression. Le soutien social est un pilier souvent sous-estimé du bien-être.

7. Faire des bilans de santé

Un suivi médical régulier permet de détecter tôt hypertension, diabète ou autres troubles silencieux. La prévention reste le meilleur investissement santé.

En adoptant progressivement ces habitudes, vous construisez une base solide pour une vie plus longue, plus énergique et plus équilibrée. Pas besoin de tout changer en une semaine : commencez par un geste, puis un autre.
TEXT,
            ],
            [
                'title' => 'L\'intelligence artificielle en 2026 : révolution ou évolution ?',
                'cover_image' => 'covers/ia-2026.jpg',
                'category' => 'Tech',
                'tags' => 'IA, intelligence artificielle, ChatGPT, technologie',
                'excerpt' => 'Assistants conversationnels, IA générative, médecine, éducation : où en est l\'IA aujourd\'hui et que nous réserve-t-elle ?',
                'content' => <<<'TEXT'
L'intelligence artificielle n'est plus un concept de science-fiction. En 2026, elle est entrée dans le quotidien de millions de personnes, transformant la façon dont nous travaillons, apprenons et créons.

L'IA générative partout

Depuis l'essor de ChatGPT et d'autres modèles de langage, l'IA générative s'est imposée dans la rédaction, le code, le design, le marketing et l'éducation. Des outils capables de produire texte, images, code ou vidéos en quelques secondes sont désormais accessibles au grand public.

Au travail : transformation, pas seulement remplacement

L'IA automatise les tâches répétitives — tri d'e-mails, synthèses, traductions, analyse de données — mais elle crée aussi de nouveaux métiers : ingénieur prompt, spécialiste en éthique de l'IA, formateur IA. Les profils qui savent collaborer avec l'IA gagnent en employabilité.

Santé et recherche

En médecine, l'IA aide au diagnostic précoce, à l'analyse d'imagerie médicale et à la découverte de molécules thérapeutiques. Elle ne remplace pas le médecin, mais lui donne des outils plus rapides et plus précis.

Éducation personnalisée

Les plateformes d'apprentissage s'appuient sur l'IA pour adapter les exercices au niveau de chaque élève, identifier les lacunes et proposer des parcours sur mesure.

Les défis à relever

Avec cette puissance viennent des questions majeures : protection des données personnelles, biais algorithmiques, deepfakes, impact environnemental des centres de données et cadre légal. L'Union européenne et d'autres régions travaillent sur des régulations pour encadrer l'usage de l'IA sans freiner l'innovation.

Vers quoi allons-nous ?

En 2026, nous sommes dans une phase d'adoption massive. Les prochaines années verront probablement l'intégration de l'IA dans les voitures, les maisons connectées, les services publics et la recherche scientifique. L'enjeu n'est plus de savoir si l'IA va changer le monde, mais comment nous allons l'utiliser de manière responsable.
TEXT,
            ],
            [
                'title' => 'Romuald Wadagni : le parcours du nouveau Président du Bénin',
                'cover_image' => 'covers/wadagni-benin.jpg',
                'category' => 'Politique',
                'tags' => 'Bénin, Romuald Wadagni, politique, Afrique',
                'excerpt' => 'De Lokossa à la présidence : retour sur le parcours de Romuald Wadagni, investi Président de la République du Bénin en mai 2026.',
                'content' => <<<'TEXT'
Romuald Wadagni, de son nom complet Kossi Mbueke Romuald Wadagni, est devenu une figure centrale de la vie politique béninoise. Né le 20 juin 1976 à Lokossa, dans le département du Mono, il incarne un parcours mêlant excellence académique, carrière internationale et engagement au service de l'État.

Une formation d'excellence

Après son baccalauréat scientifique au Bénin, il poursuit ses études en France à l'École supérieure des affaires de Grenoble (aujourd'hui IAE Grenoble), où il obtient un master en finance, major de promotion. Il complète son parcours avec une formation à Harvard, consolidant une expertise reconnue en gestion et finance.

Une carrière chez Deloitte

Romuald Wadagni rejoint le cabinet international Deloitte, où il évolue pendant près de vingt ans entre l'Europe, l'Amérique du Nord et l'Afrique. Il gravit les échelons jusqu'à devenir l'un des plus jeunes associés du cabinet et supervise une large part des opérations africaines du groupe.

Ministre de l'Économie et des Finances

En avril 2016, il répond à l'appel du président Patrice Talon et devient ministre de l'Économie et des Finances, puis ministre d'État. Pendant dix ans, il conduit des réformes budgétaires, améliore la transparence des finances publiques et participe à la redynamisation de l'économie béninoise. Il est régulièrement salué sur la scène africaine pour son rigueur et son professionnalisme.

L'élection présidentielle de 2026

En septembre 2025, la coalition au pouvoir le désigne candidat à la présidentielle. Patrice Talon, qui ne se représente pas pour un troisième mandat, lui passe le relais. Le 12 avril 2026, Romuald Wadagni remporte l'élection avec environ 94 % des voix, aux côtés de sa colistière Mariam Chabi Talata.

Investiture et nouvelle ère

Le 24 mai 2026, il prête serment au Palais des Congrès de Cotonou et devient le 9e président de la République du Bénin. Le même jour, il forme son gouvernement et ouvre une nouvelle séquence politique pour le pays.

Les défis qui l'attendent

Sécurité dans le nord du pays, coût de la vie, emploi des jeunes, relations régionales avec les pays du Sahel et consolidation des libertés : le mandat de Romuald Wadagni s'ouvre sur des enjeux majeurs. Héritier d'une décennie de réformes économiques, il doit à présent construire sa propre vision et sa marque présidentielle.

Du cabinet Deloitte aux finances publiques, de la rigueur budgétaire au palais présidentiel, le parcours de Romuald Wadagni reste l'une des trajectoires les plus remarquables de la politique béninoise contemporaine.
TEXT,
            ],
        ];

        foreach ($articles as $article) {
            Post::create([
                'user_id' => $admin->id,
                'title' => $article['title'],
                'cover_image' => $article['cover_image'] ?? null,
                'category' => $article['category'],
                'tags' => $article['tags'],
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'published' => true,
                'views' => 0,
            ]);
        }

        $this->command->info('4 articles créés avec succès !');
    }
}
