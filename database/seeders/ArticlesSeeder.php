<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Str;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'kerphilesaint@gmail.com')->first();

        if (!$admin) {
            $this->command->error('Admin user not found!');
            return;
        }

        // Vérifier si les articles existent déjà
        if (Post::count() > 0) {
            $this->command->info('Articles déjà existants, skip.');
            return;
        }

        // Article 1: Coupe du Monde 2026
        Post::create([
            'user_id' => $admin->id,
            'title' => 'Coupe du Monde 2026 : Tout ce qu\'il faut savoir',
            'slug' => Str::slug('Coupe du Monde 2026') . '-' . time(),
            'category' => 'Sport',
            'excerpt' => 'La Coupe du Monde 2026 se prépare avec des innovations majeures et un format élargi.',
            'content' => "La Coupe du Monde de football 2026 sera la première édition à réunir 48 équipes nationales, contre 32 auparavant. Organisée conjointement par les États-Unis, le Canada et le Mexique, cette compétition historique marquera un tournant dans l'histoire du football mondial.

Un format révolutionnaire

Le nouveau format comprendra 16 groupes de 3 équipes, avec les deux premiers de chaque groupe qualifiés pour les phases à élimination directe. Cette structure garantira plus de matchs et plus de spectacle pour les fans du monde entier.

Trois pays hôtes

Pour la première fois, trois nations accueilleront simultanément la Coupe du Monde. Les États-Unis accueilleront la majorité des matchs, tandis que le Canada et le Mexique organiseront respectivement 10 et 13 rencontres.

Des stades ultramodernes

Les stades sélectionnés sont parmi les plus modernes au monde, avec des capacités allant de 60 000 à 80 000 places. Des villes emblématiques comme New York, Los Angeles, Mexico et Toronto accueilleront les plus grandes rencontres.

L'impact économique

Cette Coupe du Monde générera des milliards de dollars de retombées économiques pour les trois pays hôtes, créant des milliers d'emplois et attirant des millions de touristes.

Les favoris

Le Brésil, l'Argentine, la France et l'Allemagne sont déjà considérés comme les favoris, mais le nouveau format pourrait réserver de belles surprises avec la participation de nouvelles nations qualifiées.

Un événement historique

La Coupe du Monde 2026 promet d'être l'événement sportif le plus regardé de l'histoire, avec une audience mondiale estimée à plus de 5 milliards de téléspectateurs.",
            'published' => true,
            'views' => 45,
        ]);

        // Article 2: Intelligence Artificielle
        Post::create([
            'user_id' => $admin->id,
            'title' => 'L\'Intelligence Artificielle en 2026 : Révolution ou Évolution ?',
            'slug' => Str::slug('Intelligence Artificielle 2026') . '-' . time(),
            'category' => 'Tech',
            'excerpt' => 'L\'IA transforme notre quotidien à une vitesse sans précédent. Décryptage des tendances 2026.',
            'content' => "L'intelligence artificielle n'est plus une technologie futuriste, elle fait désormais partie intégrante de notre vie quotidienne en 2026.

L'IA générative partout

Les outils comme ChatGPT, Midjourney et autres ont démocratisé l'accès à l'IA générative. En 2026, ces technologies sont utilisées dans tous les secteurs : éducation, santé, création de contenu, programmation.

L'IA dans le monde du travail

L'IA ne remplace pas les emplois, elle les transforme. Les professionnels qui maîtrisent l'IA sont devenus indispensables. De nouveaux métiers ont émergé : prompt engineers, AI trainers, ethics officers.

Santé et diagnostic

Dans le domaine médical, l'IA permet des diagnostics plus précis et plus rapides. Elle analyse des millions de données médicales pour détecter précocement des maladies comme le cancer.

Éducation personnalisée

L'IA révolutionne l'apprentissage en proposant des parcours personnalisés pour chaque étudiant, s'adaptant à son rythme et à son style d'apprentissage.

Les défis éthiques

Avec la puissance croissante de l'IA viennent des questions éthiques majeures : vie privée, biais algorithmiques, responsabilité, impact environnemental des serveurs.

Régulation et gouvernance

Les gouvernements du monde entier travaillent sur des cadres réglementaires pour encadrer l'utilisation de l'IA, tout en favorisant l'innovation.

L'avenir de l'IA

En 2026, nous ne sommes qu'au début de la révolution IA. Les experts prévoient des avancées encore plus spectaculaires dans les années à venir, notamment dans l'IA quantique et l'IA consciente.",
            'published' => true,
            'views' => 67,
        ]);

        // Article 3: Santé
        Post::create([
            'user_id' => $admin->id,
            'title' => 'Les 10 Habitudes Santé à Adopter en 2026',
            'slug' => Str::slug('10 Habitudes Santé 2026') . '-' . time(),
            'category' => 'Santé',
            'excerpt' => 'Découvrez les meilleures pratiques pour une vie saine et équilibrée selon les dernières recherches scientifiques.',
            'content' => "La santé est notre bien le plus précieux. Voici 10 habitudes scientifiquement prouvées pour améliorer votre bien-être en 2026.

1. Marcher 10 000 pas par jour

La marche reste l'exercice le plus accessible et le plus bénéfique. Elle améliore la santé cardiovasculaire, réduit le stress et maintient un poids santé.

2. Dormir 7-8 heures par nuit

Le sommeil est essentiel pour la récupération physique et mentale. Un sommeil de qualité améliore la mémoire, l'humeur et le système immunitaire.

3. Boire 2 litres d'eau par jour

L'hydratation est cruciale pour toutes les fonctions corporelles. L'eau aide à éliminer les toxines et maintient la peau en bonne santé.

4. Manger 5 fruits et légumes

Une alimentation riche en fruits et légumes fournit les vitamines et minéraux essentiels, réduit les risques de maladies chroniques.

5. Pratiquer la méditation

10 minutes de méditation quotidienne réduisent le stress, améliorent la concentration et favorisent le bien-être mental.

6. Limiter les écrans

Réduire le temps d'écran, surtout avant le coucher, améliore la qualité du sommeil et réduit la fatigue oculaire.

7. Faire du sport 3 fois par semaine

L'exercice régulier renforce le cœur, les muscles et les os, tout en libérant des endorphines pour le bien-être mental.

8. Maintenir des relations sociales

Les connexions humaines sont essentielles pour la santé mentale. Cultiver des amitiés solides réduit la dépression et l'anxiété.

9. Prendre des pauses régulières

Se lever toutes les heures, s'étirer et faire des micro-pauses améliore la productivité et réduit les douleurs.

10. Rire chaque jour

Le rire est un excellent remède naturel. Il réduit le stress, renforce le système immunitaire et améliore l'humeur.

Conclusion

Ces habitudes simples, pratiquées régulièrement, transformeront votre vie. Commencez par en adopter une ou deux, puis ajoutez-en progressivement.",
            'published' => true,
            'views' => 89,
        ]);

        // Article 4: Président WADAGNI (brouillon)
        Post::create([
            'user_id' => $admin->id,
            'title' => 'Romuald WADAGNI : De Harvard au Palais Présidentiel',
            'slug' => Str::slug('Romuald WADAGNI Président Bénin') . '-' . time(),
            'category' => 'Politique',
            'excerpt' => 'De l\'Excellence académique à la plus haute fonction : découvrez le parcours remarquable de Son Excellence Romuald WADAGNI, 9ème Président du Bénin.',
            'content' => "Un parcours académique d'exception

Né le 20 juin 1976, Kossi Mbueke Romuald WADAGNI incarne aujourd'hui la réussite d'un parcours académique exemplaire conjugué à un engagement politique au service du Bénin. Après ses études secondaires au Bénin, le jeune Romuald part en France pour poursuivre des études supérieures.

De 1995 à 1999, il étudie à l'École Supérieure des Affaires (ESA) de Grenoble, France, où il décroche un Master en Finance avec la distinction de major de promotion. Cette excellence académique n'était que le début d'une brillante trajectoire.

La consécration à Harvard

En 2007, Romuald WADAGNI franchit une nouvelle étape en intégrant la prestigieuse Harvard Business School aux États-Unis. Il y complète une formation spécialisée en private equity et venture capital, consolidant ainsi son expertise en finance internationale.

Son passage à Harvard ne s'arrête pas là : en 2017, il participe au Harvard Ministerial Leadership Program, un programme d'excellence destiné aux leaders gouvernementaux du monde entier.

Une carrière professionnelle internationale

Diplômé et certifié comme Expert-Comptable en France et Certified Public Accountant (CPA) aux États-Unis, WADAGNI rejoint le cabinet international Deloitte où il gravit rapidement les échelons pour devenir Partner et superviser les opérations africaines du groupe.

L'entrée en politique

C'est en avril 2016 que Romuald WADAGNI fait son entrée remarquée dans la vie politique béninoise en tant que Ministre d'État chargé de l'Économie et des Finances sous la présidence de Patrice TALON. Pendant dix ans, il mène des réformes économiques majeures qui transforment l'économie béninoise.

L'élection présidentielle de 2026

Le 12 avril 2026, désigné candidat de la coalition au pouvoir avec Mariam Tchabi Talata comme colistière, Romuald WADAGNI remporte l'élection présidentielle avec un score impressionnant de 94,05% des voix.

Le 24 mai 2026, il prête serment et devient officiellement le 9ème Président de la République du Bénin, formant un gouvernement de 25 membres et ouvrant une nouvelle ère pour le pays.",
            'published' => false,
            'views' => 12,
        ]);

        $this->command->info('4 articles créés avec succès!');
    }
}
