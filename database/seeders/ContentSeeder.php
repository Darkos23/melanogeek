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

            /* ── MANGA & ANIMÉ ── */
            [
                'category' => 'manga-anime',
                'title'    => 'Pourquoi les mangas touchent autant les jeunes Africains',
                'tags'     => ['manga', 'culture', 'jeunesse africaine', 'identite'],
                'body'     => <<<HTML
<h2>Une connexion qui depasse la geographie</h2>
<p>On pourrait croire que le manga — produit culturel japonais par excellence — aurait du mal a trouver sa place en Afrique. Pourtant, dans les cours de recreation d'Abidjan, de Dakar, de Lagos ou de Douala, Dragon Ball, Naruto et One Piece font partie du vocabulaire commun depuis deux decennies.</p>
<p>Pourquoi cette adhesion aussi forte ? La reponse est plus profonde qu'on ne le pense.</p>
<h2>Des themes universels, des personnages qui ressemblent a nos histoires</h2>
<p>Le manga parle de <strong>lutte, de depassement de soi, de famille, de loyaute</strong>. Ces themes ne sont pas japonais — ils sont humains. Et ils resonnent particulierement dans des societes africaines ou la notion de communaute, d'honneur familial et de perseverance face a l'adversite sont centrales.</p>
<p>Naruto, gamin rejete de son village qui devient son heros — c'est une histoire que beaucoup de jeunes africains, souvent mis de cote par des systemes qui ne les representent pas, peuvent s'approprier emotionnellement.</p>
<h2>Le manga comme espace de liberte</h2>
<p>Dans beaucoup de pays africains, la culture geek — jeux video, anime, cosplay — est encore percue comme une lubie d'Occident. Le manga offre un espace ou cette identite peut exister, se partager, se celebrer entre pairs, sans avoir besoin de validation exterieure.</p>
<p>Les clubs de lecture manga, les groupes WhatsApp de discussion, les fan arts dessines dans les cahiers de cours — tout ca constitue une <em>infrastructure culturelle informelle</em> reelle et vivante.</p>
<h2>Et apres ?</h2>
<p>La prochaine etape logique : des createurs africains qui s'approprient les codes du manga pour raconter <strong>leurs propres histoires</strong>. Pas pour imiter, mais pour hybride. La BD africaine a toujours ete forte — le manga africain peut l'etre encore plus.</p>
HTML,
            ],

            /* ── GAMING ── */
            [
                'category' => 'gaming',
                'title'    => 'Gaming en Afrique : les 5 obstacles qui freinent encore notre scene',
                'tags'     => ['gaming', 'afrique', 'esport', 'infrastructure', 'communaute'],
                'body'     => <<<HTML
<h2>Une passion reelle, des conditions difficiles</h2>
<p>La scene gaming africaine existe, est vivante, et produit des talents. Mais elle se developpe malgre des obstacles structurels que la plupart des joueurs du Nord ne connaissent pas. Voici les 5 plus critiques.</p>
<h2>1. La connexion internet</h2>
<p>Jouer en ligne avec 200ms de ping minimum, c'est jouer avec un handicap permanent. La fibre optique progresse dans les grandes villes, mais reste inaccessible ou inabordable pour la majorite. Les serveurs des grands jeux (Riot, Activision, EA) sont rarement localises sur le continent — on se connecte systematiquement a des serveurs europeens ou americains.</p>
<h2>2. Le prix du materiel</h2>
<p>Un PC gaming d'entree de gamme coute entre 600 et 900 euros. Dans beaucoup de pays africains, c'est l'equivalent de <strong>3 a 6 mois de salaire median</strong>. Les consoles recentes sont taxees a l'importation. Resultat : beaucoup jouent sur du materiel vieillissant ou sur mobile — ce qui oriente toute la scene vers le mobile gaming.</p>
<h2>3. Les paiements en ligne</h2>
<p>Acheter un jeu sur Steam, s'abonner a un service, participer a un tournoi en ligne avec prize pool — tout ca necessite une carte bancaire internationale que la majorite des Africains n'ont pas. Les solutions alternatives (Mobile Money, cartes prepayees) sont partiellement supportees mais jamais en priorite.</p>
<h2>4. L'absence de structure esport locale</h2>
<p>Des ligues esport serieuses commencent a emerger (notamment en Afrique du Sud, au Nigeria, en Egypte), mais dans beaucoup de pays, il n'existe pas de circuit organise, pas de coaches professionnels, pas de systeme de bourses pour les joueurs talentueux.</p>
<h2>5. La stigmatisation sociale</h2>
<p>Annoncer a sa famille qu'on veut "jouer aux jeux video professionnellement" reste dans beaucoup de foyers africains une conversation difficile. La legitimite sociale du gaming comme carriere est encore a construire — meme si les streamers et content creators africains font un travail enorme de normalisation.</p>
<h2>Mais ca change</h2>
<p>Ces obstacles sont reels mais pas insurmontables. Des initiatives locales, des tournois communautaires, des gaming cafes qui deviennent des hubs sociaux — la scene se construit, brique par brique.</p>
HTML,
            ],

            /* ── TECH & IA ── */
            [
                'category' => 'tech',
                'title'    => "Coder depuis l'Afrique : avantages concurrentiels qu'on ne souligne jamais assez",
                'tags'     => ['tech', 'developpement', 'remote', 'freelance', 'afrique'],
                'body'     => <<<HTML
<h2>Le narratif dominant est faux</h2>
<p>Quand on parle de tech en Afrique, le narratif dominant insiste sur les obstacles : connexion lente, manque de financement, fuite des cerveaux. Ces problemes sont reels. Mais ils masquent des <strong>avantages competitifs concrets</strong> que les developpeurs africains ont sur le marche mondial du travail remote.</p>
<h2>Le cout de la vie comme levier</h2>
<p>Un developpeur senior base a Paris ou Montreal facture 600 a 900 euros par jour. Un developpeur de niveau equivalent base a Abidjan, Nairobi ou Dakar peut proposer 150 a 300 euros — et vivre tres confortablement avec. Pour les startups et PME europeennes qui veulent du talent sans exploser leur budget, c'est une proposition de valeur immediate.</p>
<p>Ce n'est pas du dumping — c'est de l'arbitrage economique intelligent. Et les devs africains qui le comprennent en profitent.</p>
<h2>La maitrise du francais et de l'anglais</h2>
<p>La plupart des developpeurs africains francophones maitrisent deux langues europeennes couramment. Sur un marche mondial ou la communication claire avec les clients est aussi importante que les skills techniques, c'est un avantage reel face a des concurrents asiatiques par exemple.</p>
<h2>La resilience comme competence</h2>
<p>Apprendre a coder avec une connexion instable, sur un materiel limite, sans bootcamp premium ni mentorat structure — ca forge une capacite de resolution de problemes et une adaptabilite que beaucoup de devs formates dans des ecosystemes confortables n'ont pas.</p>
<h2>Comment en profiter concretement</h2>
<p>Construire un profil GitHub solide. Contribuer a des projets open source. Etre actif sur des plateformes comme Toptal, Upwork ou Contra. Documenter son travail en anglais. Le marche remote est ouvert — la porte d'entree, c'est la preuve de competence.</p>
HTML,
            ],

            /* ── DÉVELOPPEMENT ── */
            [
                'category' => 'dev',
                'title'    => 'Comment structurer son premier projet Laravel solo sans se perdre',
                'tags'     => ['laravel', 'php', 'architecture', 'bonnes pratiques', 'debutant'],
                'body'     => <<<HTML
<h2>Le probleme du projet solo</h2>
<p>Quand on decouvre Laravel, les premiers tutoriels montrent tout dans les controllers. Ca marche pour apprendre, mais des qu'on attaque un vrai projet, cette approche vire rapidement au chaos. Voici une structure simple qui tient sur la duree.</p>
<h2>La regle des responsabilites uniques</h2>
<p>Chaque fichier doit avoir <strong>une seule raison d'exister</strong>. Un controller qui valide, transforme, persiste et formate la reponse fait trop de choses. Decomposer ca aide enormement a la maintenabilite.</p>
<h2>Structure recommandee</h2>
<p>Pour un projet de taille moyenne, cette organisation fonctionne bien :</p>
<ul>
<li><strong>Controllers</strong> — uniquement recevoir la requete, appeler un service, retourner une reponse</li>
<li><strong>Services</strong> (app/Services/) — la logique metier, testable independamment</li>
<li><strong>Actions</strong> (app/Actions/) — une tache precise et atomique (ex: CreateUserAction)</li>
<li><strong>DTOs</strong> — transferer des donnees entre couches sans passer des tableaux generiques</li>
<li><strong>Policies</strong> — toute la logique d'autorisation au meme endroit</li>
</ul>
<h2>Les erreurs classiques a eviter</h2>
<p>Ne pas mettre de logique dans les vues Blade. Ne pas faire de requetes SQL dans les vues ou les controllers. Ne pas ignorer les Form Requests pour la validation — c'est l'une des fonctionnalites les plus sous-utilisees de Laravel.</p>
<h2>Quand refactoriser ?</h2>
<p>Des qu'un controller depasse 50 lignes, c'est souvent le signe qu'il fait trop. Des qu'une methode de service depasse 30 lignes, idem. Ces seuils ne sont pas des regles absolues, mais des signaux d'alerte utiles.</p>
<p>La bonne architecture n'est pas celle qui est la plus elegante sur le papier — c'est celle que toi et ton futur moi pourrez encore comprendre dans 6 mois.</p>
HTML,
            ],

            /* ── CINÉMA & SÉRIES ── */
            [
                'category' => 'cinema-series',
                'title'    => "Pourquoi le cinema africain n'a pas encore eu son moment mondial",
                'tags'     => ['cinema africain', 'nollywood', 'industrie', 'distribution', 'culture'],
                'body'     => <<<HTML
<h2>Le talent existe. Le probleme est ailleurs.</h2>
<p>Le cinema africain produit chaque annee des oeuvres remarquables — primees a Cannes, Berlin, Toronto. Des realisateurs comme Mahamat-Saleh Haroun, Mati Diop, or Chinonye Chukwu signent des films d'une profondeur rare. Pourtant, le grand public mondial ne connait pas ces noms. Pourquoi ?</p>
<h2>Le probleme de la distribution</h2>
<p>Un film africain primé a Cannes ne trouvera probablement pas de distributeur dans les grandes chaines de cinema europeennes ou americaines. Les films africains arrivent rarement sur Netflix, Prime ou Apple TV+ — et quand ils y arrivent, ce sont souvent des productions co-financees par des boites occidentales, qui gardent la main sur la distribution mondiale.</p>
<p>Nollywood est l'exception : avec plus de 2500 productions par an, c'est la deuxieme industrie cinematographique mondiale en volume. Mais sa distribution reste majoritairement continentale et diasporique.</p>
<h2>Le financement comme noeud gordien</h2>
<p>Un long-metrage africain a budget correct tourne entre 500 000 et 2 millions d'euros. C'est une somme impossible a lever localement dans la plupart des pays africains, ou les fonds d'aide au cinema sont minuscules. Les co-productions avec des pays europeens (France principalement) permettent de financer, mais imposent souvent des contraintes narratives — des histoires "exportables" selon les criteres occidentaux.</p>
<h2>Les plateformes comme opportunity</h2>
<p>Le streaming a reduit la dependance aux circuits de distribution traditionnels. Des plateformes comme <strong>Showmax</strong> (Afrique du Sud) ou <strong>Canal+ Afrique</strong> investissent dans des productions locales. C'est une opportunite reelle pour que le cinema africain trouve son audience mondiale sans passer par les gatekeepers historiques.</p>
<h2>Ce qu'il faut</h2>
<p>Des fonds nationaux du cinema renforces. Des accords de co-production Sud-Sud. Des plateformes de streaming panafricaines. Et surtout — une audience africaine qui consomme et paie pour le cinema africain. Le cercle vertueux commence par nous.</p>
HTML,
            ],

            /* ── CULTURE & SOCIÉTÉ ── */
            [
                'category' => 'culture',
                'title'    => "Le geek africain : une identite qui s'invente en temps reel",
                'tags'     => ['identite', 'culture geek', 'afrique', 'communaute', 'representation'],
                'body'     => <<<HTML
<h2>Une identite a l'intersection</h2>
<p>Etre geek en Afrique, c'est naviguer en permanence entre plusieurs mondes. Tu connais les lores de jeux video que tes parents n'ont jamais entendus. Tu debats de power systems dans des animes avec des amis en ligne que tu n'as jamais rencontres en vrai. Et tu rentres a la maison ou on te demande pourquoi tu perds ton temps avec ces "dessins animes".</p>
<p>Cette tension n'est pas unique a l'Afrique — les geeks ont toujours ete marginalises partout. Mais en Afrique, elle s'articule avec quelque chose de plus profond : la question de <strong>quelle culture tu incarnes</strong>.</p>
<h2>La culpabilite culturelle</h2>
<p>Beaucoup de geeks africains decrivent un sentiment de culpabilite diffus. Passer des heures sur des productions japonaises ou americaines pendant que des realisateurs, auteurs, developpeurs africains peinent a trouver une audience — est-ce qu'il y a une responsabilite la-dedans ?</p>
<p>La reponse courte : non, la culpabilite individuelle ne resout rien. La reponse longue : si, il y a quelque chose a faire — soutenir activement les createurs africains quand ils existent, pas seulement les consommer une fois qu'ils sont valides par l'Occident.</p>
<h2>Ce que la communaute construit</h2>
<p>Des forums, des Discord, des groupes WhatsApp, des conventions qui emergent dans les grandes villes africaines — le geek africain cree ses propres espaces. Ces espaces sont importants : ils normalisent une identite, creent des liens, et commencent a produire leurs propres references culturelles.</p>
<p>MelanoGeek existe dans cette logique. Pas pour remplacer les communautes mondiales, mais pour creer <em>un endroit ou cette identite specifique peut exister pleinement</em>.</p>
<h2>L'avenir</h2>
<p>La prochaine generation de createurs africains sera la premiere a avoir grandi avec un internet rapide, des outils de creation accessibles, et une communaute de pairs geeks pour les soutenir. Ce qu'ils vont produire va etre interessant a regarder.</p>
HTML,
            ],

            /* ── DÉBAT ── */
            [
                'category' => 'debat',
                'title'    => 'Faut-il localiser les jeux video en langues africaines ?',
                'tags'     => ['localisation', 'langues africaines', 'jeux video', 'debat', 'representation'],
                'body'     => <<<HTML
<h2>La question qui divise</h2>
<p>Des dizaines de langues africaines ont plus de locuteurs que le danois, le finnois ou le portugais europeen — et pourtant, aucun grand editeur ne propose de localisation en swahili, en yoruba, en haoussa ou en zulu. Est-ce un probleme ? Faut-il se battre pour ca ?</p>
<h2>L'argument pour</h2>
<p>La langue est le vecteur de l'immersion. Jouer dans sa langue maternelle, c'est une experience radicalement differente — les emotions passent autrement, le lore s'ancre plus profondement. Des etudes montrent que les enfants apprennent mieux dans leur langue maternelle. Pourquoi le jeu video serait-il different ?</p>
<p>Des jeux comme <em>The Witcher</em> ou <em>Cyberpunk 2077</em> ont ete localises en polonais, leur langue d'origine, avec un soin particulier. Imaginez le meme niveau d'attention pour un jeu localise en amharique ou en wolof.</p>
<h2>L'argument contre</h2>
<p>La localisation est couteuse — plusieurs centaines de milliers d'euros pour un AAA. Les editeurs localisent en fonction de la taille du marche solvable, pas de la population totale. Tant que les joueurs africains n'ont pas les moyens de payer les jeux au prix plein, la localisation ne sera pas economiquement viable pour les grands studios.</p>
<p>Et certains argueraient que l'anglais ou le francais, langues de scolarisation dans la plupart des pays africains, sont suffisants pour jouer.</p>
<h2>Une troisieme voie</h2>
<p>La localisation communautaire — comme ce qui existe pour certains logiciels open source — pourrait etre une solution intermediaire. Des fans qui traduisent benevoloment, avec le soutien (ou la tolerance) des editeurs. Ca a marche pour des films, ca peut marcher pour des jeux.</p>
<p>Et si des <strong>studios africains</strong> lancaient directement des jeux en langues africaines, sans attendre la permission des mastodontes ? Ce serait peut-etre le debut de quelque chose.</p>
<p><em>Et toi — tu jouerais a un jeu dans ta langue maternelle si c'etait disponible ?</em></p>
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

            /* ── MANGA & ANIMÉ ── */
            [
                'category' => 'manga-anime',
                'title'    => 'Vos personnages de manga preferes de tous les temps — et pourquoi ?',
                'body'     => <<<HTML
<p>Pas les plus puissants, pas les plus populaires — les personnages qui vous ont <strong>vraiment marque</strong> personnellement. Ceux dont vous pensez encore des annees apres avoir fini le manga.</p>
<p>Pour moi c'est <em>Guts de Berserk</em>. Un personnage qui refuse de se laisser definir par la souffrance qu'on lui a imposee, qui continue d'avancer malgre tout. Il n'y a pas beaucoup de personnages fictifs qui m'ont autant appris quelque chose sur la resilience.</p>
<p>Et vous ? Un personnage, une raison. Curieux de voir ce que ca dit de nous en tant que communaute.</p>
HTML,
            ],

            /* ── GAMING ── */
            [
                'category' => 'gaming',
                'title'    => 'Comment tu as decouverts le gaming ? Raconte ton histoire',
                'body'     => <<<HTML
<p>Tout le monde a une histoire d'origine avec les jeux video. La premiere console chez un cousin. Le cybercafe du quartier a 200F la session. Le vieux PC familial qui tournait a peine.</p>
<p>La mienne : un ami qui m'a amene dans sa maison pour jouer a PES sur sa PlayStation quand j'avais 10 ans. Je me souviens encore de la sensation de controler un joueur pour la premiere fois — completement addictif.</p>
<p>Racontez la votre. D'ou vous venez, quel jeu, quelle machine. Ces histoires meritent d'etre gardees quelque part.</p>
HTML,
            ],

            /* ── TECH & IA ── */
            [
                'category' => 'tech',
                'title'    => "Quel outil tech a change votre facon de travailler ou d'apprendre ?",
                'body'     => <<<HTML
<p>Pas besoin que ce soit revolutionnaire pour tout le monde — juste pour toi. Ca peut etre une app, un logiciel, un service en ligne, un accessoire.</p>
<p>Pour moi c'est <strong>Obsidian</strong> pour la prise de notes. Depuis que j'ai arrete les notes dispersees partout et commence a tout centraliser avec des liens entre les idees, ma maniere de lire et d'apprendre a completement change.</p>
<p>Vous avez quoi ? Et pourquoi ca a fait une difference pour vous specifiquement ?</p>
HTML,
            ],

            /* ── CULTURE AFRICAINE ── */
            [
                'category' => 'culture',
                'title'    => 'La culture geek est-elle compatible avec nos valeurs africaines ?',
                'body'     => <<<HTML
<p>Une question que je me pose depuis un moment et que j'ai du mal a resoudre seul.</p>
<p>D'un cote, la culture geek — anime, jeux video, SF, fantasy — est majoritairement produite au Japon et aux Etats-Unis. Elle vehicule des valeurs, des esthetiques, des visions du monde qui ne sont pas les notres.</p>
<p>De l'autre, la culture n'a jamais ete pure. Les griots ont toujours integre ce qu'ils trouvaient pertinent autour d'eux. L'hybridation est normale.</p>
<p>Mais il y a une difference entre s'inspirer et se substituer. Est-ce qu'on risque de perdre quelque chose en passant autant de temps dans des univers culturels etrangers ?</p>
<p>Je n'ai pas de reponse tranchee. Vous ?</p>
HTML,
            ],

            /* ── COSPLAY ── */
            [
                'category' => 'cosplay',
                'title'    => 'Premier cosplay : comment vous avez commence, et qu\'est-ce que vous refereriez differemment ?',
                'body'     => <<<HTML
<p>Je travaille sur mon premier cosplay en ce moment et j'ai besoin de conseils de gens qui sont passes par la.</p>
<p>Questions pratiques :</p>
<ul>
<li>Par quel personnage vous avez commence — un choix strategique ou coup de coeur ?</li>
<li>Materiaux de base quand on debute avec un budget limite ?</li>
<li>Les erreurs classiques que vous auriez voulu eviter ?</li>
</ul>
<p>Et plus generalement — qu'est-ce que le cosplay vous a apporte que vous n'attendiez pas forcement au depart ?</p>
HTML,
            ],

            /* ── OFF-TOPIC ── */
            [
                'category' => 'off-topic',
                'title'    => 'Le coin detente — partagez ce qui vous a fait sourire cette semaine',
                'body'     => <<<HTML
<p>Thread libre. Pas de sujet impose.</p>
<p>Partagez ce qui vous a rendu heureux, fait rire, surpris ou inspire cette semaine. Un meme, une decouverte, une victoire personnelle, un moment sympa avec des amis.</p>
<p>On passe beaucoup de temps a debattre de sujets serieux — c'est bien aussi de juste se retrouver et echanger des trucs positifs.</p>
<p>Moi cette semaine : j'ai fini un side project sur lequel je procrastinais depuis 4 mois. Petit mais ca fait du bien.</p>
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
