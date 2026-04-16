<?php

namespace Database\Seeders;

use App\Models\ForumThread;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Utilise le premier admin/owner, sinon le premier user
        $user = User::whereIn('role', ['admin', 'owner'])->first()
              ?? User::first();

        if (! $user) {
            $this->command->warn('Aucun utilisateur trouvé. Crée un compte d\'abord.');
            return;
        }

        $this->seedPosts($user);
        $this->seedThreads($user);

        $this->command->info('✅ Contenu généré avec succès !');
    }

    /* ─────────────────────────────────────────────────────────────
       ARTICLES DE BLOG
    ───────────────────────────────────────────────────────────── */
    private function seedPosts(User $user): void
    {
        $posts = [

            /* ── MANGA & ANIMÉ ── */
            [
                'category' => 'manga-anime',
                'title'    => 'Dandadan : pourquoi ce manga redéfinit le shōnen moderne',
                'body'     => '<h2>Un cocktail explosif entre paranormal et humour</h2>
<p>Dandadan, le manga de Yukinobu Tatsu adapté en anime par Science SARU en 2024, est l'une des œuvres les plus rafraîchissantes de ces dernières années. L'histoire suit Momo Ayase, croyante aux fantômes mais pas aux extraterrestres, et Ken Okarun, le contraire exact — leur rencontre improbable déclenche une spirale d'événements surnaturels délirants.</p>
<h2>Ce qui le rend unique</h2>
<p>Là où beaucoup de shōnen misent sur les power-ups et les tournois, Dandadan joue sur une écriture émotionnelle dense. Les personnages <strong>grandissent réellement</strong> à travers leurs épreuves, et l'humour n'efface jamais la profondeur des enjeux.</p>
<p>L'animation de Science SARU (Masaaki Yuasa) apporte une direction artistique explosive — les scènes d'action sont chorégraphiées avec une fluidité rare pour une série saisonnière.</p>
<h2>Pourquoi l'Afrique devrait s'y intéresser</h2>
<p>Le thème des esprits, des ancêtres, du surnaturel ancré dans le quotidien résonne fortement avec les mythologies africaines. Dandadan prouve qu'on peut mêler culture populaire et spiritualité sans condescendance. Exactement le type de narration que des créateurs africains devraient s'approprier.</p>
<blockquote>Dandadan n'est pas juste un anime — c'est un manifeste sur ce que la jeunesse peut créer quand elle refuse les cases.</blockquote>',
                'tags'     => ['dandadan', 'anime 2024', 'shonen', 'science saru'],
            ],

            /* ── GAMING ── */
            [
                'category' => 'gaming',
                'title'    => 'Black Myth : Wukong et la question du jeu vidéo africain',
                'body'     => '<h2>Le succès qui a tout changé</h2>
<p>Black Myth : Wukong, sorti en août 2024, a fracassé les records de Steam avec plus de <strong>2,4 millions de joueurs simultanés</strong> au lancement. Pour la première fois, un studio AAA d'Asie non-japonais (Game Science, Chine) s'impose face aux mastodontes occidentaux.</p>
<p>Le jeu s'inspire du roman classique <em>La Pérégrination vers l'Ouest</em> — une mythologie ancrée dans la culture populaire chinoise. Le résultat est un action-RPG visuellement époustouflant, revendiquant une identité culturelle forte sans compromis commerciaux.</p>
<h2>La leçon pour l'Afrique</h2>
<p>Si la Chine peut construire un AAA mondial à partir de <em>son propre patrimoine culturel</em>, pourquoi pas l'Afrique ? On a des griots, Anansi, Sundiata Keïta, les Orishas, les récits wolof ou peuls — un vivier mythologique immense encore inexploité par le jeu vidéo.</p>
<p>Des studios comme <strong>Kiro\'o Games</strong> (Cameroun) avec <em>Aurion</em> ou <strong>Pocket Sized Hands</strong> montrent que ça commence. Mais le chemin est long.</p>
<h2>Ce qu'il faut maintenant</h2>
<p>Du financement local, des incubateurs gaming, et surtout — une communauté de joueurs africains qui soutient et joue les productions du continent avant de réclamer leur visibilité mondiale.</p>',
                'tags'     => ['gaming', 'black myth wukong', 'jeu video africain', 'aaa'],
            ],

            /* ── TECH & IA ── */
            [
                'category' => 'tech',
                'title'    => 'L'IA générative en Afrique : opportunité réelle ou hype occidentale ?',
                'body'     => '<h2>Le contexte en 2025</h2>
<p>ChatGPT, Gemini, Claude, Mistral — les outils d'IA générative se multiplient à une vitesse vertigineuse. Mais qui en profite réellement ? Une étude de l\'Oxford Internet Institute révèle que <strong>78% des données d'entraînement</strong> des grands modèles de langage proviennent d'Amérique du Nord et d'Europe. L'Afrique sub-saharienne représente moins de 1%.</p>
<h2>Conséquence directe : des biais massifs</h2>
<p>Demande à ChatGPT de générer une image "typique d'un entrepreneur africain" — tu obtiendras probablement quelque chose qui n'a rien à voir avec la réalité. Ces biais ne sont pas anodins : ils influencent les outils de recrutement, de crédit, de santé déployés sur le continent.</p>
<h2>Les initiatives qui changent la donne</h2>
<p><strong>Masakhane</strong>, communauté de chercheurs africains en NLP, travaille sur des modèles entraînés sur des langues comme le swahili, le yoruba ou le haoussa. <strong>AI4D Africa</strong> finance des projets d'IA alignés sur les besoins locaux : agriculture, santé, éducation.</p>
<h2>La vraie question</h2>
<p>L'IA peut automatiser des tâches répétitives, accélérer la productivité, démocratiser l'accès au savoir. Mais pour que ça profite à l'Afrique, il faut des <em>ingénieurs africains qui construisent ces systèmes</em>, pas seulement des consommateurs qui les utilisent.</p>
<blockquote>L'IA est neutre comme un marteau est neutre. Tout dépend de qui le tient.</blockquote>',
                'tags'     => ['ia', 'afrique', 'tech', 'intelligence artificielle', 'masakhane'],
            ],

            /* ── DÉVELOPPEMENT ── */
            [
                'category' => 'dev',
                'title'    => 'Pourquoi les devs africains devraient parier sur Laravel en 2025',
                'body'     => '<h2>Le marché freelance explose</h2>
<p>Selon les derniers rapports de Toptal et Upwork, la demande pour les développeurs Laravel a augmenté de <strong>34% en 2024</strong>. Laravel reste le framework PHP le plus populaire, et sa courbe d\'apprentissage accessible en fait un outil idéal pour les marchés émergents.</p>
<h2>Laravel vs les alternatives</h2>
<p>Beaucoup de bootcamps africains poussent React/Next.js ou Django — des choix valides, mais qui nécessitent un écosystème JS mature ou une maîtrise de Python. Laravel, lui, permet de <strong>livrer des projets complets full-stack</strong> (backend, API, auth, jobs, notifications, paiement) avec un seul langage et un outillage cohérent.</p>
<p>Pour un freelance ou une agence qui livre des projets e-commerce, SaaS ou plateformes communautaires pour des PME africaines, c'est un avantage concurrentiel énorme.</p>
<h2>L\'écosystème local</h2>
<p>Des communautés comme <strong>Laravel Cameroon</strong>, <strong>PHP Sénégal</strong> ou le groupe <em>Laravel Côte d\'Ivoire</em> sur WhatsApp comptent des centaines de développeurs actifs. Les meetups hybrides post-Covid reprennent.</p>
<h2>Par où commencer</h2>
<p>Le meilleur point d'entrée reste <a href="https://laracasts.com">Laracasts</a> pour les vidéos, et la documentation officielle qui est exemplaire. En 6 mois de pratique régulière, on peut livrer des projets professionnels.</p>',
                'tags'     => ['laravel', 'php', 'developpement', 'freelance', 'afrique dev'],
            ],

            /* ── CINÉMA & SÉRIES ── */
            [
                'category' => 'cinema-series',
                'title'    => 'Arcane saison 2 : le chef-d\'œuvre d\'animation qui humilie Hollywood',
                'body'     => '<h2>Le retour tant attendu</h2>
<p>Arcane saison 2, diffusée sur Netflix en novembre 2024, a mis fin à trois ans d'attente avec une conclusion à la hauteur des attentes — et même au-delà. Produite par Fortiche Productions (Paris) pour Riot Games, la série confirme que l'animation européenne peut rivaliser et surpasser ce que Hollywood produit.</p>
<h2>Ce qui la distingue</h2>
<p>Le style visuel de Fortiche — mélange de peinture numérique, de motion capture et de 2D expressif — reste unique. Chaque plan est un tableau. Mais c'est surtout l'<strong>écriture des personnages féminins</strong> (Vi, Jinx, Caitlyn, Mel) qui impressionne : complexes, contradictoires, jamais réduites à leurs relations amoureuses.</p>
<p>La saison 2 explore les thèmes de la <em>révolution, de la trahison de classe et du trauma intergénérationnel</em> avec une maturité rare dans une production issue du jeu vidéo.</p>
<h2>La leçon pour le cinéma africain</h2>
<p>Arcane montre qu'un studio relativement petit (400 personnes chez Fortiche) peut créer quelque chose de mondial avec une vision artistique forte et un soutien financier suffisant. Des pays comme le Nigeria, le Sénégal ou la Côte d'Ivoire ont des talents d'animation réels. Il manque les structures de financement.</p>',
                'tags'     => ['arcane', 'netflix', 'animation', 'fortiche', 'riot games'],
            ],

            /* ── CULTURE & SOCIÉTÉ ── */
            [
                'category' => 'culture',
                'title'    => 'Afrofuturisme 2025 : quand la culture africaine réinvente demain',
                'body'     => '<h2>Au-delà de Black Panther</h2>
<p>L'afrofuturisme n'est pas un genre né avec Black Panther — il remonte aux années 1960 avec Sun Ra, Octavia Butler, Samuel R. Delany. Mais depuis 2018, le mouvement a explosé dans la culture populaire mondiale, et 2025 marque une étape : <strong>les créateurs africains reprennent la main</strong> sur leur propre futurisme.</p>
<h2>Au Nigeria, en Côte d'Ivoire, au Sénégal</h2>
<p>Le collectif <em>Àṣà Futura</em> à Lagos produit des courts-métrages SF ancrés dans la cosmologie yoruba. À Abidjan, le label <strong>Afrobeats Cosmos</strong> explore les thèmes de l'espace et de l'IA dans ses productions. À Dakar, des auteurs comme Fatou Diome et Mohamed Mbougar Sarr ouvrent des perspectives littéraires qui dépassent le réalisme post-colonial.</p>
<h2>Le jeu vidéo et la bande dessinée</h2>
<p>La BD africaine connaît un renouveau remarquable. Des séries comme <em>Anansi le géant</em> ou les mangas africains produits à Abidjan mêlent esthétique manga et mythologies locales. C'est exactement ce type de hybridation qui définit l'afrofuturisme de demain.</p>
<blockquote>L'afrofuturisme ne demande pas la permission. Il construit son propre vaisseau.</blockquote>',
                'tags'     => ['afrofuturisme', 'culture africaine', 'sf', 'creation'],
            ],

            /* ── DÉBAT ── */
            [
                'category' => 'debat',
                'title'    => 'Le piratage de contenu en Afrique : crime ou nécessité économique ?',
                'body'     => '<h2>La réalité des chiffres</h2>
<p>En Afrique subsaharienne, un abonnement Netflix représente en moyenne <strong>8 à 15% du salaire minimum mensuel</strong> selon les pays. Un jeu AAA à 70€ est inaccessible pour 90% de la population. La question du piratage ne peut pas être réduite à un simple problème moral.</p>
<h2>L'argument culturel</h2>
<p>Des générations entières de créatifs africains ont été formés au cinéma, à la musique, à l'animation grâce au contenu piraté. Le réalisateur qui aujourd'hui signe des films primés à Cannes a peut-être regardé ses premiers Kubrick sur un DVD gravé à Douala. Ce n'est pas une anecdote — c'est une infrastructure culturelle de fait.</p>
<h2>L'argument contre</h2>
<p>Les artistes africains sont aussi victimes du piratage. Un musicien d'Abidjan qui sort un album voit son œuvre disponible gratuitement le lendemain du lancement. Les plateformes de streaming africaines comme Audiomack ou Boomplay peinent à monétiser dans un contexte de piratage massif.</p>
<h2>Quelle solution ?</h2>
<p>Des modèles de prix régionaux existent déjà (Spotify Premium Afrique, Netflix Africa pricing) mais restent insuffisants. La vraie réponse est peut-être dans des <strong>modèles économiques radicalement différents</strong> — freemium, micro-paiements, financement par les diasporas.</p>
<p><em>Et toi, qu'est-ce que tu en penses ? Le piratage t'a-t-il forgé culturellement ?</em></p>',
                'tags'     => ['piratage', 'debat', 'acces culture', 'afrique', 'streaming'],
            ],
        ];

        foreach ($posts as $data) {
            // Ne pas dupliquer si le titre existe déjà
            if (Post::where('title', $data['title'])->exists()) {
                continue;
            }

            $user->posts()->create([
                'title'        => $data['title'],
                'body'         => $data['body'],
                'category'     => $data['category'],
                'tags'         => $data['tags'],
                'is_published' => true,
                'created_at'   => now()->subDays(rand(1, 20)),
            ]);
        }
    }

    /* ─────────────────────────────────────────────────────────────
       SUJETS FORUM
    ───────────────────────────────────────────────────────────── */
    private function seedThreads(User $user): void
    {
        $threads = [

            /* ── MANGA & ANIMÉ ── */
            [
                'category' => 'manga-anime',
                'title'    => 'Quel anime de 2024 mérite vraiment son hype ?',
                'body'     => '<p>On est dans une période où absolument tout reçoit le label "anime de la saison" sur les réseaux. <strong>Dandadan, Frieren, Delicious in Dungeon, Mushishi</strong>… les recommandations fusent de partout.</p>
<p>Mais honnêtement, lesquels ont tenu leurs promesses sur la durée ?</p>
<p>Pour moi, <em>Frieren : Beyond Journey\'s End</em> reste la meilleure chose que j\'ai regardée depuis des années. L\'angle du deuil et du temps long traité avec cette délicatesse, c\'est rare dans le médium.</p>
<p>Et vous ? Quel anime 2024 vous a vraiment marqué — pas juste impressionné visuellement, mais touché quelque chose ?</p>',
            ],

            /* ── GAMING ── */
            [
                'category' => 'gaming',
                'title'    => 'Vous jouez à quoi en ce moment ? Partage ta config et tes jeux du moment',
                'body'     => '<p>Petit thread pour qu\'on se partage nos setups et ce qu\'on a dans la rotation en ce moment.</p>
<p>De mon côté :</p>
<ul>
<li><strong>PC :</strong> RTX 3060, i5-12400, 16GB RAM — pas le setup ultime mais ça tourne</li>
<li><strong>En cours :</strong> Elden Ring (oui je sais, en retard), Balatro, et quelques parties de Valorant</li>
<li><strong>Envie de jouer :</strong> Black Myth Wukong mais mon CPU pleure à l\'idée</li>
</ul>
<p>Et toi ? Setup ? Jeux du moment ? Coups de cœur récents ?</p>
<p>Bonus si tu joues depuis le continent — curieux de savoir les specs moyennes et les contraintes qu\'on gère ici (connexion, prix des jeux, etc.)</p>',
            ],

            /* ── TECH & IA ── */
            [
                'category' => 'tech',
                'title'    => 'L\'IA va-t-elle tuer le métier de développeur en Afrique avant qu\'il décolle ?',
                'body'     => '<p>Question qui me trotte dans la tête depuis un moment.</p>
<p>On parle beaucoup de la révolution du coding en Afrique — bootcamps, formations, talents qui émergent. Mais en parallèle, des outils comme <strong>GitHub Copilot, Cursor, Claude</strong> automatisent une part croissante du travail de dev junior.</p>
<p>Est-ce qu\'on est en train de former des développeurs pour un marché qui va se rétrécir au moment où ils arrivent ?</p>
<p>Ou au contraire, l\'IA va-t-elle <em>augmenter</em> les devs africains et leur permettre de rivaliser avec des équipes bien plus grandes ?</p>
<p>Je suis dev moi-même et j\'utilise Claude quotidiennement — ma productivité a au moins doublé. Mais j\'ai aussi du mal à voir comment ça se passe pour quelqu\'un qui débute.</p>',
            ],

            /* ── CULTURE AFRICAINE ── */
            [
                'category' => 'culture',
                'title'    => 'Mangas africains vs manga japonais : faut-il vraiment choisir ?',
                'body'     => '<p>Depuis quelques années, des maisons d\'édition comme <strong>Kugali</strong> (Nigeria/USA) ou des auteurs comme <em>Roye Okupe</em> publient des BD africaines avec des codes manga. Résultat : un hybride qui divise.</p>
<p>Certains adorent — enfin du contenu qui ressemble à eux, avec des héros africains dans des univers SF/fantasy ancrés dans les mythologies locales.</p>
<p>D\'autres critiquent — pourquoi imiter le manga japonais ? Pourquoi ne pas créer une esthétique proprement africaine ?</p>
<p>Ma position personnelle : l\'hybridation culturelle c\'est justement ce qui fait avancer les arts. Le manga lui-même est né d\'une hybridation avec la BD américaine et européenne.</p>
<p>Mais je veux vous entendre — vous lisez des BD/mangas africains ? Lesquels ? Et vous pensez quoi de cette tendance ?</p>',
            ],

            /* ── COSPLAY ── */
            [
                'category' => 'cosplay',
                'title'    => 'Cosplay en Afrique : les défis qu\'on ne voit jamais dans les vidéos YouTube',
                'body'     => '<p>On voit beaucoup de cosplays incroyables en ligne — des réalisations de folie avec des centaines d\'heures de travail. Mais on parle rarement des <strong>contraintes spécifiques</strong> au cosplay sur le continent.</p>
<p>Quelques points de friction que j\'ai vécus personnellement :</p>
<ul>
<li>L\'EVA foam est quasi introuvable localement, il faut importer (et payer les taxes)</li>
<li>Les conventions sont rares et souvent concentrées dans les capitales</li>
<li>La chaleur... 35°C en armure de résine, c\'est une autre expérience</li>
<li>Le regard des gens qui ne comprennent pas du tout ce que tu fais</li>
</ul>
<p>Mais aussi des avantages : les tissus locaux (wax, kente, bogolan) dans des cosplays donnent des résultats absolument uniques qu\'on ne voit nulle part ailleurs.</p>
<p>Vous cosplayez ? Vous gérez comment ces contraintes ?</p>',
            ],

            /* ── OFF-TOPIC ── */
            [
                'category' => 'off-topic',
                'title'    => 'Café ou thé pour coder ? Le débat qui ne finira jamais',
                'body'     => '<p>On est entre geeks, on peut parler des vraies questions importantes.</p>
<p>Personnellement : <strong>café robusta local</strong>, bien serré, pas de sucre. C\'est la seule façon de survivre aux sessions de debug jusqu\'à 3h du matin.</p>
<p>Mais j\'ai des collègues qui jurent par le thé à la menthe marocain pour la concentration. Et d\'autres qui codent à l\'Iboudaan ou au bissap glacé.</p>
<p>Alors — café, thé, jus local, energy drink honteux ? Et est-ce que votre boisson favorite change selon le type de tâche (feature, bug fix, revue de code) ?</p>
<p>Partagez vos rituels de code. On a besoin de savoir.</p>',
            ],
        ];

        foreach ($threads as $data) {
            if (ForumThread::where('title', $data['title'])->exists()) {
                continue;
            }

            $user->forumThreads()->create([
                'title'         => $data['title'],
                'body'          => $data['body'],
                'category'      => $data['category'],
                'last_reply_at' => now()->subDays(rand(0, 10)),
                'views_count'   => rand(12, 340),
                'created_at'    => now()->subDays(rand(1, 15)),
            ]);
        }
    }
}
