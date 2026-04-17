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
        $user = User::whereIn('role', ['admin', 'owner'])->first()
              ?? User::first();

        if (! $user) {
            throw new \RuntimeException('Aucun utilisateur trouve. Cree un compte dabord.');
        }

        $this->seedPosts($user);
        $this->seedThreads($user);
    }

    private function seedPosts(User $user): void
    {
        $posts = [

            [
                'category' => 'manga-anime',
                'title'    => 'Dandadan : pourquoi ce manga redefinie le shonen moderne',
                'tags'     => ['dandadan', 'anime 2024', 'shonen', 'science saru'],
                'body'     => <<<HTML
<h2>Un cocktail explosif entre paranormal et humour</h2>
<p>Dandadan, le manga de Yukinobu Tatsu adapte en anime par Science SARU en 2024, est l'une des oeuvres les plus rafraichissantes de ces dernieres annees. L'histoire suit Momo Ayase, croyante aux fantomes mais pas aux extraterrestres, et Ken Okarun — leur rencontre improbable declanche une spirale d'evenements surnaturels delirants.</p>
<h2>Ce qui le rend unique</h2>
<p>La ou beaucoup de shonen misent sur les power-ups et les tournois, Dandadan joue sur une ecriture emotionnelle dense. Les personnages <strong>grandissent reellement</strong> a travers leurs epreuves, et l'humour n'efface jamais la profondeur des enjeux.</p>
<p>L'animation de Science SARU apporte une direction artistique explosive — les scenes d'action sont choregrafiees avec une fluidite rare pour une serie saisonniere.</p>
<h2>Pourquoi l'Afrique devrait s'y interesser</h2>
<p>Le theme des esprits, des ancetres, du surnaturel ancre dans le quotidien resonne fortement avec les mythologies africaines. Dandadan prouve qu'on peut meler culture populaire et spiritualite sans condescendance.</p>
<blockquote>Dandadan n'est pas juste un anime — c'est un manifeste sur ce que la jeunesse peut creer quand elle refuse les cases.</blockquote>
HTML,
            ],

            [
                'category' => 'gaming',
                'title'    => 'Black Myth Wukong et la question du jeu video africain',
                'tags'     => ['gaming', 'black myth wukong', 'jeu video africain', 'aaa'],
                'body'     => <<<HTML
<h2>Le succes qui a tout change</h2>
<p>Black Myth : Wukong, sorti en aout 2024, a fracasse les records de Steam avec plus de <strong>2,4 millions de joueurs simultanees</strong> au lancement. Pour la premiere fois, un studio AAA d'Asie non-japonais (Game Science, Chine) s'impose face aux mastodontes occidentaux.</p>
<p>Le jeu s'inspire du roman classique <em>La Peregrination vers l'Ouest</em> — une mythologie ancree dans la culture populaire chinoise.</p>
<h2>La lecon pour l'Afrique</h2>
<p>Si la Chine peut construire un AAA mondial a partir de <em>son propre patrimoine culturel</em>, pourquoi pas l'Afrique ? On a des griots, Anansi, Sundiata Keita, les Orishas — un vivier mythologique immense encore inexploite par le jeu video.</p>
<p>Des studios comme <strong>Kiro'o Games</strong> (Cameroun) avec <em>Aurion</em> montrent que ca commence. Mais le chemin est long.</p>
<h2>Ce qu'il faut maintenant</h2>
<p>Du financement local, des incubateurs gaming, et surtout — une communaute de joueurs africains qui soutient et joue les productions du continent.</p>
HTML,
            ],

            [
                'category' => 'tech',
                'title'    => "L'IA generative en Afrique : opportunite reelle ou hype occidentale ?",
                'tags'     => ['ia', 'afrique', 'tech', 'intelligence artificielle', 'masakhane'],
                'body'     => <<<HTML
<h2>Le contexte en 2025</h2>
<p>ChatGPT, Gemini, Claude, Mistral — les outils d'IA generative se multiplient a une vitesse vertigineuse. Mais qui en profite reellement ? Une etude de l'Oxford Internet Institute revele que <strong>78% des donnees d'entrainement</strong> des grands modeles de langage proviennent d'Amerique du Nord et d'Europe. L'Afrique sub-saharienne represente moins de 1%.</p>
<h2>Consequence directe : des biais massifs</h2>
<p>Demande a ChatGPT de generer une image d'un entrepreneur africain typique — tu obtiendras probablement quelque chose qui n'a rien a voir avec la realite. Ces biais ne sont pas anodins : ils influencent les outils de recrutement, de credit, de sante deployes sur le continent.</p>
<h2>Les initiatives qui changent la donne</h2>
<p><strong>Masakhane</strong>, communaute de chercheurs africains en NLP, travaille sur des modeles entraines sur des langues comme le swahili, le yoruba ou le haoussa. <strong>AI4D Africa</strong> finance des projets d'IA alignes sur les besoins locaux.</p>
<blockquote>L'IA est neutre comme un marteau est neutre. Tout depend de qui le tient.</blockquote>
HTML,
            ],

            [
                'category' => 'dev',
                'title'    => 'Pourquoi les devs africains devraient parier sur Laravel en 2025',
                'tags'     => ['laravel', 'php', 'developpement', 'freelance', 'afrique dev'],
                'body'     => <<<HTML
<h2>Le marche freelance explose</h2>
<p>Selon les derniers rapports de Toptal et Upwork, la demande pour les developpeurs Laravel a augmente de <strong>34% en 2024</strong>. Laravel reste le framework PHP le plus populaire, et sa courbe d'apprentissage accessible en fait un outil ideal pour les marches emergents.</p>
<h2>Laravel vs les alternatives</h2>
<p>Beaucoup de bootcamps africains poussent React ou Django — des choix valides, mais qui necessitent un ecosysteme mature. Laravel, lui, permet de <strong>livrer des projets complets full-stack</strong> (backend, API, auth, jobs, notifications, paiement) avec un seul langage et un outillage coherent.</p>
<p>Pour un freelance ou une agence qui livre des projets pour des PME africaines, c'est un avantage concurrentiel enorme.</p>
<h2>Par ou commencer</h2>
<p>Le meilleur point d'entree reste Laracasts pour les videos, et la documentation officielle qui est exemplaire. En 6 mois de pratique reguliere, on peut livrer des projets professionnels.</p>
HTML,
            ],

            [
                'category' => 'cinema-series',
                'title'    => "Arcane saison 2 : le chef-d'oeuvre d'animation qui humilie Hollywood",
                'tags'     => ['arcane', 'netflix', 'animation', 'fortiche', 'riot games'],
                'body'     => <<<HTML
<h2>Le retour tant attendu</h2>
<p>Arcane saison 2, diffusee sur Netflix en novembre 2024, a mis fin a trois ans d'attente avec une conclusion a la hauteur des attentes. Produite par Fortiche Productions (Paris) pour Riot Games, la serie confirme que l'animation europeenne peut rivaliser et surpasser ce que Hollywood produit.</p>
<h2>Ce qui la distingue</h2>
<p>Le style visuel de Fortiche reste unique. Mais c'est surtout l'<strong>ecriture des personnages feminins</strong> (Vi, Jinx, Caitlyn, Mel) qui impressionne : complexes, contradictoires, jamais reduites a leurs relations amoureuses.</p>
<p>La saison 2 explore les themes de la <em>revolution, de la trahison de classe et du trauma intergenerationnel</em> avec une maturite rare dans une production issue du jeu video.</p>
<h2>La lecon pour le cinema africain</h2>
<p>Arcane montre qu'un studio relativement petit peut creer quelque chose de mondial avec une vision artistique forte. Des pays comme le Nigeria ou le Senegal ont des talents d'animation reels. Il manque les structures de financement.</p>
HTML,
            ],

            [
                'category' => 'culture',
                'title'    => "Afrofuturisme 2025 : quand la culture africaine reinvente demain",
                'tags'     => ['afrofuturisme', 'culture africaine', 'sf', 'creation'],
                'body'     => <<<HTML
<h2>Au-dela de Black Panther</h2>
<p>L'afrofuturisme n'est pas un genre ne avec Black Panther — il remonte aux annees 1960 avec Sun Ra, Octavia Butler, Samuel R. Delany. Mais depuis 2018, le mouvement a explose dans la culture populaire mondiale, et 2025 marque une etape : <strong>les createurs africains reprennent la main</strong> sur leur propre futurisme.</p>
<h2>Au Nigeria, en Cote d'Ivoire, au Senegal</h2>
<p>Le collectif <em>Asa Futura</em> a Lagos produit des courts-metrages SF ancres dans la cosmologie yoruba. A Abidjan, des labels explorent les themes de l'espace et de l'IA dans leurs productions musicales.</p>
<h2>Le jeu video et la bande dessinee</h2>
<p>La BD africaine connait un renouveau remarquable. Des series qui melent esthetique manga et mythologies locales — c'est exactement ce type d'hybridation qui definit l'afrofuturisme de demain.</p>
<blockquote>L'afrofuturisme ne demande pas la permission. Il construit son propre vaisseau.</blockquote>
HTML,
            ],

            [
                'category' => 'debat',
                'title'    => 'Le piratage de contenu en Afrique : crime ou necessite economique ?',
                'tags'     => ['piratage', 'debat', 'acces culture', 'afrique', 'streaming'],
                'body'     => <<<HTML
<h2>La realite des chiffres</h2>
<p>En Afrique subsaharienne, un abonnement Netflix represente en moyenne <strong>8 a 15% du salaire minimum mensuel</strong> selon les pays. Un jeu AAA a 70 euros est inaccessible pour 90% de la population. La question du piratage ne peut pas etre reduite a un simple probleme moral.</p>
<h2>L'argument culturel</h2>
<p>Des generations entieres de creatifs africains ont ete formes au cinema, a la musique, a l'animation grace au contenu pirate. Le realisateur qui aujourd'hui signe des films primes a Cannes a peut-etre regarde ses premiers Kubrick sur un DVD grave a Douala.</p>
<h2>L'argument contre</h2>
<p>Les artistes africains sont aussi victimes du piratage. Un musicien d'Abidjan qui sort un album voit son oeuvre disponible gratuitement le lendemain du lancement.</p>
<h2>Quelle solution ?</h2>
<p>Des modeles de prix regionaux existent deja mais restent insuffisants. La vraie reponse est peut-etre dans des <strong>modeles economiques radicalement differents</strong> — freemium, micro-paiements, financement par les diasporas.</p>
<p><em>Et toi, qu'est-ce que tu en penses ? Le piratage t'a-t-il forge culturellement ?</em></p>
HTML,
            ],
        ];

        foreach ($posts as $data) {
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

    private function seedThreads(User $user): void
    {
        $threads = [

            [
                'category' => 'manga-anime',
                'title'    => 'Quel anime de 2024 merite vraiment son hype ?',
                'body'     => <<<HTML
<p>On est dans une periode ou absolument tout recoit le label "anime de la saison" sur les reseaux. <strong>Dandadan, Frieren, Delicious in Dungeon</strong>... les recommandations fusent de partout.</p>
<p>Mais honnetement, lesquels ont tenu leurs promesses sur la duree ?</p>
<p>Pour moi, <em>Frieren : Beyond Journey's End</em> reste la meilleure chose que j'ai regardee depuis des annees. L'angle du deuil et du temps long traite avec cette delicatesse, c'est rare dans le medium.</p>
<p>Et vous ? Quel anime 2024 vous a vraiment marque ?</p>
HTML,
            ],

            [
                'category' => 'gaming',
                'title'    => 'Vous jouez a quoi en ce moment ? Partage ta config et tes jeux du moment',
                'body'     => <<<HTML
<p>Petit thread pour qu'on se partage nos setups et ce qu'on a dans la rotation en ce moment.</p>
<p>De mon cote :</p>
<ul>
<li><strong>PC :</strong> RTX 3060, i5-12400, 16GB RAM</li>
<li><strong>En cours :</strong> Elden Ring, Balatro, quelques parties de Valorant</li>
<li><strong>Envie de jouer :</strong> Black Myth Wukong mais mon CPU pleure a l'idee</li>
</ul>
<p>Et toi ? Setup ? Jeux du moment ? Bonus si tu joues depuis le continent — curieux de savoir les specs moyennes et les contraintes qu'on gere ici.</p>
HTML,
            ],

            [
                'category' => 'tech',
                'title'    => "L'IA va-t-elle tuer le metier de developpeur en Afrique avant qu'il decolle ?",
                'body'     => <<<HTML
<p>Question qui me trotte dans la tete depuis un moment.</p>
<p>On parle beaucoup de la revolution du coding en Afrique — bootcamps, formations, talents qui emergent. Mais en parallele, des outils comme <strong>GitHub Copilot, Cursor, Claude</strong> automatisent une part croissante du travail de dev junior.</p>
<p>Est-ce qu'on est en train de former des developpeurs pour un marche qui va se retrecir au moment ou ils arrivent ?</p>
<p>Ou au contraire, l'IA va-t-elle <em>augmenter</em> les devs africains et leur permettre de rivaliser avec des equipes bien plus grandes ?</p>
<p>Je suis dev moi-meme et j'utilise Claude quotidiennement — ma productivite a au moins double. Mais j'ai du mal a voir comment ca se passe pour quelqu'un qui debute.</p>
HTML,
            ],

            [
                'category' => 'culture',
                'title'    => 'Mangas africains vs manga japonais : faut-il vraiment choisir ?',
                'body'     => <<<HTML
<p>Depuis quelques annees, des maisons d'edition comme <strong>Kugali</strong> (Nigeria) publient des BD africaines avec des codes manga. Resultat : un hybride qui divise.</p>
<p>Certains adorent — enfin du contenu qui ressemble a eux, avec des heros africains dans des univers SF ancres dans les mythologies locales.</p>
<p>D'autres critiquent — pourquoi imiter le manga japonais ? Pourquoi ne pas creer une esthetique proprement africaine ?</p>
<p>Ma position : l'hybridation culturelle c'est justement ce qui fait avancer les arts. Le manga lui-meme est ne d'une hybridation avec la BD americaine et europeenne.</p>
<p>Vous lisez des BD/mangas africains ? Lesquels ? Et vous pensez quoi de cette tendance ?</p>
HTML,
            ],

            [
                'category' => 'cosplay',
                'title'    => 'Cosplay en Afrique : les defis qu\'on ne voit jamais dans les videos YouTube',
                'body'     => <<<HTML
<p>On voit beaucoup de cosplays incroyables en ligne. Mais on parle rarement des <strong>contraintes specifiques</strong> au cosplay sur le continent.</p>
<p>Quelques points de friction que j'ai vecus :</p>
<ul>
<li>L'EVA foam est quasi introuvable localement, il faut importer</li>
<li>Les conventions sont rares et souvent concentrees dans les capitales</li>
<li>La chaleur... 35 degres en armure de resine, c'est une autre experience</li>
<li>Le regard des gens qui ne comprennent pas ce que tu fais</li>
</ul>
<p>Mais aussi des avantages : les tissus locaux (wax, kente, bogolan) dans des cosplays donnent des resultats absolument uniques qu'on ne voit nulle part ailleurs.</p>
<p>Vous cosplayez ? Vous gerez comment ces contraintes ?</p>
HTML,
            ],

            [
                'category' => 'off-topic',
                'title'    => 'Cafe ou the pour coder ? Le debat qui ne finira jamais',
                'body'     => <<<HTML
<p>On est entre geeks, on peut parler des vraies questions importantes.</p>
<p>Personnellement : <strong>cafe robusta local</strong>, bien serre, pas de sucre. C'est la seule facon de survivre aux sessions de debug jusqu'a 3h du matin.</p>
<p>Mais j'ai des collegues qui jurent par le the a la menthe marocain pour la concentration. Et d'autres qui codent au bissap glace.</p>
<p>Alors — cafe, the, jus local, energy drink honteux ? Est-ce que votre boisson favorite change selon le type de tache (feature, bug fix, revue de code) ?</p>
<p>Partagez vos rituels de code.</p>
HTML,
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
