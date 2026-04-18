<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class BabstaroiSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le compte Babstaroi existant
        $user = User::where('username', 'babstaroi')->firstOrFail();

        // ── Article 1 ──────────────────────────────────────────────────────
        Post::create([
            'user_id'    => $user->id,
            'title'      => 'J\'ai utilisé Claude, Gemini et ChatGPT pendant 3 mois — voilà ce que personne ne te dit',
            'body'       => <<<HTML
<p>Bon. Je vais être honnête avec vous parce que j'en ai marre des articles "comparatifs" d'IA qui sont écrits par des gens qui ont fait trois prompts et qui se sont arrêtés là.</p>

<p>Moi j'ai passé trois mois à utiliser Claude, Gemini et ChatGPT dans mon quotidien — pour du code, pour écrire, pour analyser des trucs, pour des projets perso. Pas pour faire un test propre avec des benchmarks. Pour <em>vraiment</em> les utiliser. Et j'ai des choses à dire.</p>

<h2>ChatGPT : le pote qui connaît tout mais qui invente quand il sait pas</h2>

<p>GPT-4o reste impressionnant. Aucune discussion. Pour générer du code rapidement, pour brainstormer, pour avoir une réponse rapide sur n'importe quel sujet — c'est encore là que je vais en premier par réflexe, parce que c'est ce que j'ai utilisé le plus longtemps.</p>

<p>Le problème ? Il hallucine avec une confiance que je trouve presque offensante. Il va t'inventer une librairie Python, te donner le nom d'un auteur qui n'existe pas, citer une étude avec un lien qui mène nulle part — et tout ça avec le même ton calme et assuré. Si tu vérifies pas derrière, tu te plantes.</p>

<p>Pour du code qui tourne dans un environnement que tu maîtrises, il est excellent. Pour de la recherche ou des faits précis, vérifie systématiquement.</p>

<h2>Gemini : Google a de l'argent mais pas encore de l'âme</h2>

<p>J'avais de grands espoirs pour Gemini parce que Google a accès à une quantité de données que les autres n'ont pas. Et sur certaines choses — la recherche en temps réel, les résultats récents — il est clairement meilleur.</p>

<p>Mais pour écrire, pour raisonner sur des problèmes complexes, pour tenir une conversation longue et cohérente... il fatigue vite. Il perd le fil. Il répète des choses qu'il a déjà dites. Et ses réponses ont souvent ce côté "réponse d'entreprise" — poli, formaté, un peu vide.</p>

<p>Je l'utilise principalement pour la recherche et pour les trucs qui nécessitent des infos récentes. Pour le reste, je passe mon tour.</p>

<h2>Claude : celui dont on parle pas assez</h2>

<p>Anthropic est moins connue qu'OpenAI ou Google. Claude a moins de bruit autour de lui. Et c'est pour ça que beaucoup de gens passent à côté du meilleur modèle pour écrire et raisonner.</p>

<p>Sur des tâches longues et complexes — analyser un document de 50 pages, maintenir le contexte d'une conversation qui dure des heures, écrire du texte qui ne ressemble pas à du texte AI — Claude est dans une autre catégorie. Il fait des erreurs, comme tous les autres. Mais quand il ne sait pas, il le dit. Ce détail change tout quand tu travailles dessus sérieusement.</p>

<p>Son défaut : il est parfois trop prudent. Il refuse des trucs qui sont complètement inoffensifs parce que l'algorithme de sécurité sur-réagit. Frustrant.</p>

<h2>Ce que j'ai vraiment appris</h2>

<p>Le truc que personne ne dit dans ces comparatifs, c'est que la question "lequel est le meilleur ?" est la mauvaise question. La bonne question c'est : pour quoi faire ?</p>

<p>Ce que j'utilise maintenant : ChatGPT pour le code et les idées rapides. Claude pour écrire, analyser, raisonner. Gemini quand j'ai besoin d'infos récentes.</p>

<p>Et surtout — j'ai arrêté de traiter ces outils comme des oracles. C'est des outils de travail. Comme un marteau. Un marteau de ouf, certes. Mais un marteau quand même.</p>

<p>Qu'est-ce que vous utilisez vous ? Et pour faire quoi ? Je suis curieux de savoir si vous avez les mêmes expériences.</p>
HTML,
            'category'   => 'ia',
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);

        // ── Article 2 ──────────────────────────────────────────────────────
        Post::create([
            'user_id'    => $user->id,
            'title'      => 'Le "vibe coding" va-t-il tuer le développeur junior africain ?',
            'body'       => <<<HTML
<p>Il y a quelques semaines, Andrej Karpathy — un des cerveaux derrière les premières versions de GPT — a popularisé le terme "vibe coding". L'idée : tu décris ce que tu veux, l'IA écrit le code, toi tu "acceptes tout sans vraiment lire". Tu construis sur des vibrations.</p>

<p>La communauté tech mondiale s'est emballée. Des gens ont construit des apps en 2 heures. Des no-coders sont devenus "développeurs". Et moi j'ai regardé tout ça avec un sentiment bizarre — entre l'excitation et une vraie inquiétude pour les devs juniors africains qui commencent maintenant.</p>

<h2>D'abord, soyons clairs : le vibe coding fonctionne</h2>

<p>Je vais pas faire semblant. J'ai moi-même construit des trucs en vibe coding que j'aurais mis des jours à coder proprement. Une landing page, un script d'automatisation, un petit outil interne. Ça marche. C'est rapide. Et quand tu sais ce que tu fais, tu peux valider le code généré et corriger les erreurs.</p>

<p>Le problème, c'est le "quand tu sais ce que tu fais".</p>

<h2>Le dev junior africain est dans une position particulière</h2>

<p>En Europe ou aux États-Unis, un junior qui sort d'une école ou d'un bootcamp entre dans une entreprise où il y a des seniors. Il apprend en voyant du vrai code en production. Il fait des code reviews. Il se fait corriger.</p>

<p>En Afrique, beaucoup de devs juniors travaillent en freelance dès le départ, seuls, sur des petits projets pour des clients locaux. Il n'y a pas de senior derrière eux pour valider. Pas de code review. Pas de culture de la documentation.</p>

<p>Si ces devs-là adoptent le vibe coding sans avoir les bases solides pour évaluer ce que l'IA génère — ils vont construire des trucs qui marchent <em>en apparence</em>, mais qui sont fragiles, non-maintenables, et potentiellement dangereux (sécurité, données personnelles, etc.).</p>

<h2>Mais voilà l'autre côté</h2>

<p>Je me suis assis avec cette inquiétude pendant quelques semaines, et puis j'ai réalisé que j'étais peut-être en train de regarder les choses à l'envers.</p>

<p>Les barrières à l'entrée dans le dev ont toujours été plus hautes pour les jeunes africains. Pas d'accès à des machines puissantes dans toutes les villes. Internet qui coûte cher. Peu de mentors locaux. Des ressources qui sont quasi-exclusivement en anglais.</p>

<p>L'IA efface une partie de ces barrières. Un lycéen à Bamako avec une connexion correcte peut maintenant construire une application web fonctionnelle. Est-ce qu'il comprend tout ce qu'il construit ? Non. Mais est-ce qu'il pouvait le faire avant ? Non plus.</p>

<h2>Ma position finale</h2>

<p>Le vibe coding n'est ni une bénédiction ni une malédiction. C'est un amplificateur. Il amplifie ce que tu apportes déjà.</p>

<p>Si tu as des bases solides, il te rend 10 fois plus productif. Si tu n'as pas les bases, il te donne l'illusion de compétence — et cette illusion peut coûter cher à toi et à tes clients.</p>

<p>Mon conseil aux juniors africains qui lisent ceci : utilisez les outils IA. Absolument. Mais en parallèle, comprenez ce que vous faites tourner. Lisez le code généré ligne par ligne au début. Cassez des trucs intentionnellement pour comprendre pourquoi ça casse. Les bases ne sont pas optionnelles — elles sont ce qui vous permettra de tirer le maximum de ces outils dans 2 ans quand ils seront encore plus puissants.</p>

<p>Le futur appartient à ceux qui savent diriger les machines, pas juste les utiliser.</p>
HTML,
            'category'   => 'dev',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->command->info('✅ Babstaroi créé avec 2 articles publiés.');
    }
}
