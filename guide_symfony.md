# Guide complet Symfony - De zéro à l'authentification

## 📋 Prérequis
Vous avez installé Symfony avec : `docker-compose exec php composer create-project symfony/skeleton . --no-interaction`

---

## 1️⃣ Créer votre première page

### Installer les dépendances nécessaires
```bash
# Installer le Maker Bundle (pour générer du code facilement)
docker-compose exec php composer require symfony/maker-bundle --dev

# Installer Twig (moteur de templates) et les annotations
docker-compose exec php composer require twig annotations
```

### Créer votre premier contrôleur
```bash
# Générer automatiquement un contrôleur
# J'ai ajouté un "php" pour que le code fonctionne dans ma configuration.
# Sinon ça ne passe pas au niveau de ma config : www-data
docker-compose exec php php bin/console make:controller HomeController
```

### Fichier créé : `src/Controller/HomeController.php`
```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // Route : définit l'URL qui déclenche cette méthode
    // name : identifiant unique pour générer des liens
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // render() : affiche un template Twig
        // Le 2ème paramètre passe des variables au template
        return $this->render('home/index.html.twig', [
            'titre' => 'Ma première page',
            'message' => 'Bienvenue sur Symfony!'
        ]);
    }
}
```

### Template créé : `templates/home/index.html.twig`
```twig
{# Hérite du template de base #}
{% extends 'base.html.twig' %}

{# Définit le titre de la page #}
{% block title %}{{ titre }}{% endblock %}

{# Contenu principal de la page #}
{% block body %}
<div style="padding: 20px;">
    <h1>{{ titre }}</h1>
    <p>{{ message }}</p>
</div>
{% endblock %}
```

### Tester votre page
Visitez : `http://localhost` (ou votre URL Docker)

---

## 2️⃣ Créer les liens avec une autre page

### Créer une deuxième page
```bash
# Créer un contrôleur pour la page "À propos"
docker-compose exec php php bin/console make:controller AboutController
```

### Fichier : `src/Controller/AboutController.php`
```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    // Route avec un nom personnalisé pour la page "À propos"
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'titre' => 'À propos de nous',
        ]);
    }
}
```

### Modifier `templates/home/index.html.twig` pour ajouter des liens
```twig
{% extends 'base.html.twig' %}

{% block title %}{{ titre }}{% endblock %}

{% block body %}
<div style="padding: 20px;">
    <h1>{{ titre }}</h1>
    <p>{{ message }}</p>
    
    {# Créer un lien vers une autre route #}
    {# path() génère l'URL à partir du nom de la route #}
    <nav>
        <ul>
            <li><a href="{{ path('app_home') }}">Accueil</a></li>
            <li><a href="{{ path('app_about') }}">À propos</a></li>
        </ul>
    </nav>
</div>
{% endblock %}
```

### Créer un menu dans `templates/base.html.twig`
```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
    </head>
    <body>
        {# Menu de navigation global #}
        <nav style="background: #333; padding: 10px;">
            <a href="{{ path('app_home') }}" style="color: white; margin-right: 15px;">Accueil</a>
            <a href="{{ path('app_about') }}" style="color: white; margin-right: 15px;">À propos</a>
            
        </nav>
        
        {% block body %}{% endblock %}
        
        {% block javascripts %}{% endblock %}
    </body>
</html>
```

---

## 3️⃣ Créer un formulaire

### Installer les dépendances pour les formulaires
```bash
# Installer le composant Form et Validator
docker-compose exec php composer require symfony/form symfony/validator
```

### Créer un contrôleur pour le formulaire de contact
```bash
docker-compose exec php php bin/console make:controller ContactController
```

### Fichier : `src/Controller/ContactController.php`
```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        // Créer un formulaire directement dans le contrôleur
        $form = $this->createFormBuilder()
            // Champ "nom" : texte obligatoire
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire']),
                    new Assert\Length(['min' => 2, 'minMessage' => 'Le nom doit faire au moins 2 caractères'])
                ]
            ])
            // Champ "email" : format email valide
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                    new Assert\Email(['message' => 'Email invalide'])
                ]
            ])
            // Champ "message" : zone de texte
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le message est obligatoire']),
                    new Assert\Length(['min' => 10, 'minMessage' => 'Le message doit faire au moins 10 caractères'])
                ]
            ])
            // Bouton de soumission
            ->add('envoyer', SubmitType::class, [
                'label' => 'Envoyer le message'
            ])
            ->getForm();

        // Traiter les données envoyées par le formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();
            
            // Ici vous pouvez traiter les données (envoyer un email, sauvegarder en BDD, etc.)
            // Pour l'exemple, on affiche juste un message de succès
            $this->addFlash('success', 'Merci ' . $data['nom'] . ', votre message a été envoyé !');
            
            // Rediriger vers la page d'accueil après succès
            return $this->redirectToRoute('app_home');
        }

        // Afficher le formulaire
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
```

### Template : `templates/contact/index.html.twig`
```twig
{% extends 'base.html.twig' %}

{% block title %}Contact{% endblock %}

{% block body %}
<div style="padding: 20px; max-width: 600px;">
    <h1>Formulaire de contact</h1>
    
    {# Afficher le formulaire avec la fonction form() #}
    {{ form_start(contactForm) }}
        
        {# form_row() affiche le label, le champ et les erreurs #}
        {{ form_row(contactForm.nom) }}
        {{ form_row(contactForm.email) }}
        {{ form_row(contactForm.message) }}
        
        {# Bouton de soumission #}
        {{ form_row(contactForm.envoyer, {'attr': {'class': 'btn btn-primary'}}) }}
        
    {{ form_end(contactForm) }}
</div>
{% endblock %}
```

### Afficher les messages flash dans `templates/base.html.twig`
```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
    </head>
    <body>
        <nav style="background: #333; padding: 10px;">
            <a href="{{ path('app_home') }}" style="color: white; margin-right: 15px;">Accueil</a>
            <a href="{{ path('app_about') }}" style="color: white; margin-right: 15px;">À propos</a>
            <a href="{{ path('app_contact') }}" style="color: white;">Contact</a>
        </nav>
        
        {# Afficher les messages flash (succès, erreur, etc.) #}
        {% for label, messages in app.flashes %}
            {% for message in messages %}
                <div style="padding: 10px; margin: 10px; background: {% if label == 'success' %}#4CAF50{% else %}#f44336{% endif %}; color: white;">
                    {{ message }}
                </div>
            {% endfor %}
        {% endfor %}
        
        {% block body %}{% endblock %}
    </body>
</html>
```

---

## 4️⃣ Créer la base de données MySQL

### Installer Doctrine (ORM pour gérer la BDD)
```bash
# Installer Doctrine ORM
docker-compose exec php composer require symfony/orm-pack
```

### Configurer la connexion MySQL dans `.env`
```bash
# Fichier .env à la racine du projet
# Modifier la ligne DATABASE_URL :

# Format : mysql://utilisateur:motdepasse@hote:port/nom_base?serverVersion=version
DATABASE_URL="mysql://root:password@mysql:3306/symfony_app?serverVersion=8.0"

# Explication :
# root : utilisateur MySQL (à adapter selon votre docker-compose)
# password : mot de passe MySQL
# mysql : nom du service dans docker-compose.yml
# 3306 : port MySQL
# symfony_app : nom de la base de données à créer
# serverVersion=8.0 : version de MySQL (à adapter)
```

### Créer la base de données
```bash
# Créer la base de données si elle n'existe pas
docker-compose exec php bin/console doctrine:database:create

# Vérifier que la connexion fonctionne
docker-compose exec php bin/console doctrine:schema:validate
```

### Créer votre première entité (table)
```bash
# Créer une entité "Article" par exemple
docker-compose exec php bin/console make:entity Article

# Le maker vous posera des questions :
# - Nom de la propriété : title (type: string, length: 255)
# - Nom de la propriété : content (type: text)
# - Nom de la propriété : createdAt (type: datetime)
# - Appuyez sur Entrée pour terminer
```

### Fichier créé : `src/Entity/Article.php`
```php
<?php
namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity] : indique que c'est une table en base de données
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    // Clé primaire auto-incrémentée
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Colonne "title" de type VARCHAR(255)
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // Colonne "content" de type TEXT
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    // Colonne "created_at" de type DATETIME
    #[ORM\Column]
    private ?\DateTimeInterface $createdAt = null;

    // Getters et Setters générés automatiquement
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
```

### Créer les tables en base de données
```bash
# Créer une migration (fichier qui contient les instructions SQL)
docker-compose exec php bin/console make:migration

# Exécuter la migration (créer réellement les tables)
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```

---

## 5️⃣ Pouvoir se connecter avec un utilisateur

### Installer le bundle de sécurité
```bash
# Installer les composants de sécurité
docker-compose exec php composer require symfony/security-bundle
```

### Créer l'entité User
```bash
# Générer l'entité User avec make:user
docker-compose exec php php bin/console make:user

# Questions posées :
# - Nom de la classe : User (par défaut)
# - Stocker les utilisateurs en BDD : yes
# - Propriété pour l'identifiant unique : email
# - Hash les mots de passe : yes
```

### Fichier créé : `src/Entity/User.php`
```php
<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email : identifiant unique de l'utilisateur
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Rôles : tableau de rôles (ROLE_USER, ROLE_ADMIN, etc.)
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe hashé
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // getUserIdentifier() : retourne l'identifiant unique (email)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantir que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    // eraseCredentials() : nettoie les données sensibles temporaires
    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires sensibles, nettoyez-les ici
    }
}
```

### Créer la table User en base de données
```bash
# Créer la migration
docker-compose exec php php bin/console make:migration

# Exécuter la migration
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### Créer le système d'authentification
```bash
# Générer un système de login complet
docker-compose exec php php bin/console make:auth
# dépressier pour le drernier Symfony
docker-compose exec php php bin/console make:security
# Besoin de le détailler
# -> Test d'une version avec un utilisateur principal et secondaire
# ici on prendrais l'customer_user en principal et administration_user en secondaire
# test de form login : 
docker-compose exec php php bin/console make:security:form-login

# Questions posées :
# - Type d'authentification : 1 (Login form authenticator)
# - Nom de la classe : AppAuthenticator
# - Nom du contrôleur : SecurityController
# - Générer logout : yes
```

### Fichier créé : `src/Controller/SecurityController.php`
```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route pour afficher le formulaire de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, rediriger vers l'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    // Route pour la déconnexion (gérée automatiquement par Symfony)
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide, Symfony gère la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
```

### Template : `templates/security/login.html.twig`
```twig
{% extends 'base.html.twig' %}

{% block title %}Connexion{% endblock %}

{% block body %}
<div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
    <h1>Connexion</h1>

    {# Afficher l'erreur de connexion si elle existe #}
    {% if error %}
        <div style="padding: 10px; background: #f44336; color: white; margin-bottom: 10px;">
            {{ error.messageKey|trans(error.messageData, 'security') }}
        </div>
    {% endif %}

    <form method="post">
        <div style="margin-bottom: 15px;">
            <label for="inputEmail">Email</label>
            <input 
                type="email" 
                value="{{ last_username }}" 
                name="email" 
                id="inputEmail" 
                required 
                autofocus
                style="width: 100%; padding: 8px;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="inputPassword">Mot de passe</label>
            <input 
                type="password" 
                name="password" 
                id="inputPassword" 
                required
                style="width: 100%; padding: 8px;"
            >
        </div>

        {# Token CSRF pour sécuriser le formulaire #}
        <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">

        <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;">
            Se connecter
        </button>
    </form>
    
    <p style="margin-top: 20px;">
        <a href="{{ path('app_register') }}">Créer un compte</a>
    </p>
</div>
{% endblock %}
```

### Créer un formulaire d'inscription
```bash
# Générer un contrôleur d'inscription
docker-compose exec php bin/console make:registration-form

# Questions :
# - Ajouter une annotation #[UniqueEntity] sur User : yes
# - Envoyer un email de vérification : no (pour simplifier)
# - Authentifier automatiquement après inscription : yes
# - Route de redirection : app_home
```

### Fichier créé : `src/Controller/RegistrationController.php`
```php
<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager
    ): Response
    {
        // Créer un nouvel utilisateur
        $user = new User();
        
        // Créer le formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe en clair
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Sauvegarder l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre compte a été créé avec succès !');

            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
```

### Modifier le menu dans `templates/base.html.twig`
```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
    </head>
    <body>
        <nav style="background: #333; padding: 10px;">
            <a href="{{ path('app_home') }}" style="color: white; margin-right: 15px;">Accueil</a>
            <a href="{{ path('app_about') }}" style="color: white; margin-right: 15px;">À propos</a>
            <a href="{{ path('app_contact') }}" style="color: white; margin-right: 15px;">Contact</a>
            
            {# Afficher différents liens selon l'état de connexion #}
            {% if app.user %}
                {# Si l'utilisateur est connecté #}
                <span style="color: white; margin-right: 15px;">Bonjour {{ app.user.email }}</span>
                <a href="{{ path('app_logout') }}" style="color: white;">Déconnexion</a>
            {% else %}
                {# Si l'utilisateur n'est pas connecté #}
                <a href="{{ path('app_login') }}" style="color: white; margin-right: 15px;">Connexion</a>
                <a href="{{ path('app_register') }}" style="color: white;">Inscription</a>
            {% endif %}
        </nav>
        
        {# Messages flash #}
        {% for label, messages in app.flashes %}
            {% for message in messages %}
                <div style="padding: 10px; margin: 10px; background: {% if label == 'success' %}#4CAF50{% else %}#f44336{% endif %}; color: white;">
                    {{ message }}
                </div>
            {% endfor %}
        {% endfor %}
        
        {% block body %}{% endblock %}
    </body>
</html>
```

### Protéger une page (accessible uniquement aux utilisateurs connectés)
```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    // #[IsGranted] : seuls les utilisateurs connectés peuvent accéder à cette page
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        // $this->getUser() : récupère l'utilisateur connecté
        $user = $this->getUser();
        
        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
        ]);
    }
}
```

---

## 📝 Commandes utiles

```bash
# Lister toutes les routes
docker-compose exec php bin/console debug:router

# Créer une nouvelle entité
docker-compose exec php bin/console make:entity

# Créer un nouveau contrôleur
docker-compose exec php bin/console make:controller

# Créer une migration
docker-compose exec php bin/console make:migration

# Exécuter les migrations
docker-compose exec php bin/console doctrine:migrations:migrate

# Vider le cache
docker-compose exec php bin/console cache:clear

# Créer un utilisateur en ligne de commande (utile pour tester)
docker-compose exec php bin/console security:hash-password
```

---

## 🎯 Résumé complet

1. ✅ **Première page** : HomeController + template Twig
2. ✅ **Liens entre pages** : Fonction `path()` dans Twig
3. ✅ **Formulaire** : ContactController avec validation
4. ✅ **Base de données MySQL** : Configuration + entités Doctrine
5. ✅ **Authentification** : User, login, register, logout

Vous avez maintenant une application Symfony complète avec authentification ! 🚀

Bonjour l'étudiant ! Excellente question qui touche au cœur de la modélisation des relations utilisateurs. Vous voulez créer un système où un administrateur principal peut créer des comptes secondaires qui lui sont liés. C'est un cas d'usage très courant (comptes entreprise/employés, famille/parents/enfants, etc.). Laissez-moi vous guider vers la solution la plus simple et élégante avec Symfony. 🎯

## 🧠 Comprendre le besoin : une relation "parent-enfant" entre utilisateurs

Ce que vous décrivez est une **relation réflexive** ou **auto-référencée** : un utilisateur peut être lié à un autre utilisateur de la même table. C'est exactement comme une catégorie qui peut avoir des sous-catégories, mais appliqué aux utilisateurs .

En base de données, cela se traduit par :
- Une seule table `user`
- Une colonne qui fait référence à un autre `id` de la même table

## 🏗️ SOLUTION 1 : L'approche simple avec relation ManyToOne (recommandée)

C'est la méthode la plus simple et la plus performante. Un utilisateur principal peut avoir plusieurs comptes secondaires.

### Étape 1 : Créer l'entité User avec une relation vers elle-même

```bash
# Si ce n'est pas déjà fait, créez votre entité User
php bin/console make:user
```

Ensuite, ajoutez la relation réflexive :

```bash
php bin/console make:entity User
```

Répondez aux questions :
- `New property name (press <return> to stop adding fields):` → **primaryAccount**
- `Field type (enter ? to see all types):` → **relation**
- `What class should this entity be related to?:` → **User**
- `Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:` → **ManyToOne**
- `Is the User.primaryAccount property allowed to be null (nullable)? (yes/no)` → **yes** (car l'utilisateur principal n'a pas de "parent")
- `Do you want to add a new property to User so that you can access/update the secondary accounts from it? (yes/no)` → **yes** (créer la relation inverse)
- `New field name inside User:` → **secondaryAccounts**
- `Is the User.secondaryAccounts property allowed to be null (nullable)? (yes/no)` → **no** (une collection peut être vide)
- `Do you want to automatically delete orphaned accounts (orphanRemoval)? (yes/no)` → **no** (ou yes selon vos règles métier)

### Étape 2 : Ce que Symfony génère

```php
// src/Entity/User.php
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // ... vos champs existants (email, password, roles...)

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'secondaryAccounts')]
    private ?User $primaryAccount = null;

    #[ORM\OneToMany(mappedBy: 'primaryAccount', targetEntity: self::class)]
    private Collection $secondaryAccounts;

    public function __construct()
    {
        $this->secondaryAccounts = new ArrayCollection();
    }

    public function getPrimaryAccount(): ?User
    {
        return $this->primaryAccount;
    }

    public function setPrimaryAccount(?User $primaryAccount): static
    {
        $this->primaryAccount = $primaryAccount;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSecondaryAccounts(): Collection
    {
        return $this->secondaryAccounts;
    }

    public function addSecondaryAccount(User $secondaryAccount): static
    {
        if (!$this->secondaryAccounts->contains($secondaryAccount)) {
            $this->secondaryAccounts->add($secondaryAccount);
            $secondaryAccount->setPrimaryAccount($this);
        }
        return $this;
    }

    public function removeSecondaryAccount(User $secondaryAccount): static
    {
        if ($this->secondaryAccounts->removeElement($secondaryAccount)) {
            if ($secondaryAccount->getPrimaryAccount() === $this) {
                $secondaryAccount->setPrimaryAccount(null);
            }
        }
        return $this;
    }
}
```

### Étape 3 : Créer et exécuter la migration

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Étape 4 : Utiliser cette relation dans vos contrôleurs

```php
// src/Controller/UserManagementController.php
#[Route('/admin/users')]
class UserManagementController extends AbstractController
{
    #[Route('/create-secondary', name: 'app_create_secondary')]
    public function createSecondary(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Récupérer l'utilisateur connecté (qui doit être un compte principal)
        $primaryUser = $this->getUser();
        
        // Vérifier que cet utilisateur a le droit de créer des comptes secondaires
        // (vous pouvez définir ce droit via un rôle ou simplement vérifier qu'il n'est pas déjà secondaire)
        if ($primaryUser->getPrimaryAccount() !== null) {
            throw $this->createAccessDeniedException('Un compte secondaire ne peut pas créer d\'autres comptes');
        }

        $secondaryUser = new User();
        $form = $this->createForm(SecondaryUserType::class, $secondaryUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lier le compte secondaire au compte principal
            $secondaryUser->setPrimaryAccount($primaryUser);
            
            // Hasher le mot de passe
            $secondaryUser->setPassword(
                $passwordHasher->hashPassword($secondaryUser, $form->get('plainPassword')->getData())
            );
            
            // Donner un rôle spécifique si besoin (ex: ROLE_SECONDARY_USER)
            $secondaryUser->setRoles(['ROLE_SECONDARY_USER']);

            $em->persist($secondaryUser);
            $em->flush();

            $this->addFlash('success', 'Compte secondaire créé avec succès !');
            return $this->redirectToRoute('app_list_secondary');
        }

        return $this->render('user/create_secondary.html.twig', [
            'form' => $form->createView(),
            'primaryUser' => $primaryUser
        ]);
    }

    #[Route('/list-secondary', name: 'app_list_secondary')]
    public function listSecondary(): Response
    {
        $primaryUser = $this->getUser();
        
        // Récupérer tous les comptes secondaires
        $secondaryAccounts = $primaryUser->getSecondaryAccounts();

        return $this->render('user/list_secondary.html.twig', [
            'secondaryAccounts' => $secondaryAccounts
        ]);
    }
}
```

### Étape 5 : Créer le formulaire dédié

```bash
php bin/console make:form SecondaryUserType
```

```php
// src/Form/SecondaryUserType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecondaryUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email du compte secondaire',
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire'])
                ]
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
```

## 🏗️ SOLUTION 2 : L'approche avec table de liaison (ManyToMany)

Si vous avez besoin d'une hiérarchie plus complexe (un utilisateur peut avoir plusieurs comptes principaux, ou les relations peuvent être plus souples), une relation ManyToMany avec une table de liaison peut être plus adaptée .

```php
// Dans l'entité User
#[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'secondaryOf')]
#[ORM\JoinTable(name: 'user_hierarchy')]
#[ORM\JoinColumn(name: 'primary_user_id', referencedColumnName: 'id')]
#[ORM\InverseJoinColumn(name: 'secondary_user_id', referencedColumnName: 'id')]
private Collection $secondaryAccounts;

#[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'secondaryAccounts')]
private Collection $secondaryOf;
```

**Mais attention** : cette approche est plus complexe et rarement nécessaire pour un simple système parent-enfant .

## 🔒 Gestion des permissions avec un Voter

Pour contrôler finement qui peut modifier quoi, créez un **Voter**  :

```bash
php bin/console make:voter UserVoter
```

```php
// src/Security/UserVoter.php
class UserVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const CREATE_SECONDARY = 'create_secondary';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::CREATE_SECONDARY])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        
        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var User $targetUser */
        $targetUser = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($targetUser, $currentUser),
            self::EDIT => $this->canEdit($targetUser, $currentUser),
            self::CREATE_SECONDARY => $this->canCreateSecondary($currentUser),
            default => false
        };
    }

    private function canView(User $targetUser, User $currentUser): bool
    {
        // Un utilisateur peut voir ses propres comptes secondaires
        // et son compte principal peut le voir
        return $targetUser === $currentUser 
            || $targetUser->getPrimaryAccount() === $currentUser
            || $currentUser->getPrimaryAccount() === $targetUser;
    }

    private function canEdit(User $targetUser, User $currentUser): bool
    {
        // Seul le compte principal peut modifier ses comptes secondaires
        return $targetUser->getPrimaryAccount() === $currentUser;
    }

    private function canCreateSecondary(User $currentUser): bool
    {
        // Un compte principal peut créer des comptes secondaires
        return $currentUser->getPrimaryAccount() === null;
    }
}
```

## 📊 Récapitulatif des deux approches

| Critère | Solution 1 (ManyToOne) | Solution 2 (ManyToMany) |
|---------|------------------------|--------------------------|
| **Complexité** | Simple | Plus complexe |
| **Performance** | Optimale (1 table, 1 clé étrangère) | Moins performante (table de jointure) |
| **Cas d'usage** | 1 compte principal → N comptes secondaires | N comptes principaux → N comptes secondaires |
| **Recommandation** | ✅ À privilégier pour 95% des cas | ⚠️ Uniquement si besoin avéré |

## 💡 Conseils pratiques

1. **Ajoutez un champ `type`** pour distinguer visuellement les types de comptes :
```php
#[ORM\Column(length: 20, nullable: true)]
private ?string $accountType = 'primary'; // 'primary' ou 'secondary'
```

2. **Dans votre formulaire d'inscription principal**, vérifiez qu'on ne crée pas un compte secondaire par erreur :
```php
// Dans RegistrationController
$user->setAccountType('primary');
```

3. **Pour la création de comptes secondaires**, limitez l'accès :
```php
#[IsGranted('create_secondary', 'user')]
```

## 🎯 En résumé

La solution la plus simple et élégante pour votre besoin est **l'approche avec relation ManyToOne réflexive**  :

1. Une seule entité `User`
2. Un champ `primaryAccount` qui référence un autre `User`
3. Un champ `secondaryAccounts` pour la relation inverse
4. Un Voter pour gérer les permissions

Cette approche est :
- **Naturelle** : elle reflète exactement votre besoin
- **Performante** : une seule table, pas de jointures complexes
- **Maintenable** : facile à comprendre et à faire évoluer

N'hésitez pas si vous avez besoin de précisions sur l'implémentation ou si vous voulez explorer un cas particulier ! 😊

Bonjour l'étudiant ! Absolument, c'est non seulement possible, mais c'est exactement ce que la solution que je vous ai proposée permet de faire. Laissez-moi vous rassurer et vous expliquer pourquoi cela fonctionne parfaitement pour plusieurs utilisateurs principaux. 🎯

## ✅ La réponse courte : OUI, c'est parfaitement possible !

Avec la relation ManyToOne réflexive que je vous ai présentée, **chaque utilisateur principal peut gérer SES PROPRES utilisateurs secondaires** de manière totalement indépendante.

## 🧠 Comment ça fonctionne concrètement ?

Prenons un exemple concret pour bien visualiser :

```
UTILISATEURS PRINCIPAUX (chacun a son propre "groupe")
├── Jean (id: 1)
│   ├── Sophie (id: 4, primaryAccount_id: 1) → Secondaire de Jean
│   ├── Marc (id: 5, primaryAccount_id: 1)   → Secondaire de Jean
│   └── Léa (id: 6, primaryAccount_id: 1)    → Secondaire de Jean
│
├── Marie (id: 2)
│   ├── Paul (id: 7, primaryAccount_id: 2)   → Secondaire de Marie
│   └── Julie (id: 8, primaryAccount_id: 2)  → Secondaire de Marie
│
└── Pierre (id: 3)
    ├── Luc (id: 9, primaryAccount_id: 3)    → Secondaire de Pierre
    └── Emma (id: 10, primaryAccount_id: 3)  → Secondaire de Pierre
```

Dans cette structure :
- **Jean** ne voit que Sophie, Marc et Léa
- **Marie** ne voit que Paul et Julie  
- **Pierre** ne voit que Luc et Emma

**Chaque compte principal a son propre "espace" isolé** des autres.

## 🔧 Vérification et corrections potentielles

### 1. Vérifiez que votre entité est bien configurée

```php
// src/Entity/User.php - Vérifions que la relation est correcte
class User implements UserInterface
{
    // ... vos autres champs

    // Relation vers le compte principal (ManyToOne = plusieurs secondaires peuvent pointer vers un principal)
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'secondaryAccounts')]
    private ?User $primaryAccount = null;

    // Relation inverse (OneToMany = un principal peut avoir plusieurs secondaires)
    #[ORM\OneToMany(mappedBy: 'primaryAccount', targetEntity: self::class)]
    private Collection $secondaryAccounts;

    public function __construct()
    {
        $this->secondaryAccounts = new ArrayCollection();
    }

    // Méthode utilitaire pour vérifier si l'utilisateur est un compte principal
    public function isPrimaryAccount(): bool
    {
        return $this->primaryAccount === null;
    }

    // Méthode utilitaire pour vérifier si l'utilisateur est un compte secondaire
    public function isSecondaryAccount(): bool
    {
        return $this->primaryAccount !== null;
    }

    // Récupérer le vrai "propriétaire" (utile pour les permissions)
    public function getRootAccount(): User
    {
        $user = $this;
        while ($user->getPrimaryAccount() !== null) {
            $user = $user->getPrimaryAccount();
        }
        return $user;
    }
}
```

### 2. Dans votre contrôleur de création de compte secondaire

```php
// src/Controller/UserManagementController.php
#[Route('/secondary-users')]
class SecondaryUserController extends AbstractController
{
    #[Route('/create', name: 'app_secondary_create')]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // L'utilisateur connecté est automatiquement le compte principal
        /** @var User $primaryUser */
        $primaryUser = $this->getUser();
        
        // Vérification de sécurité : seul un compte principal peut créer des secondaires
        if (!$primaryUser->isPrimaryAccount()) {
            throw $this->createAccessDeniedException('Seuls les comptes principaux peuvent créer des utilisateurs secondaires.');
        }

        $secondaryUser = new User();
        $form = $this->createForm(SecondaryUserType::class, $secondaryUser, [
            'primary_user' => $primaryUser // On passe le principal au formulaire si besoin
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lien avec le compte principal connecté
            $secondaryUser->setPrimaryAccount($primaryUser);
            
            // Hash du mot de passe
            $secondaryUser->setPassword(
                $passwordHasher->hashPassword($secondaryUser, $form->get('plainPassword')->getData())
            );
            
            // Optionnel : donner un rôle spécifique
            $secondaryUser->setRoles(['ROLE_SECONDARY_USER']);

            $em->persist($secondaryUser);
            $em->flush();

            $this->addFlash('success', 'Le compte secondaire a été créé avec succès.');
            return $this->redirectToRoute('app_secondary_list');
        }

        return $this->render('secondary_user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_secondary_list')]
    public function list(): Response
    {
        /** @var User $primaryUser */
        $primaryUser = $this->getUser();
        
        // Récupère UNIQUEMENT les secondaires de CE principal
        $secondaryAccounts = $primaryUser->getSecondaryAccounts();

        return $this->render('secondary_user/list.html.twig', [
            'secondaryAccounts' => $secondaryAccounts,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_secondary_edit')]
    public function edit(User $secondaryUser, Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        
        // Vérification cruciale : ce secondaire appartient-il bien au principal connecté ?
        if ($secondaryUser->getPrimaryAccount() !== $currentUser) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce compte car il ne vous appartient pas.');
        }

        $form = $this->createForm(SecondaryUserType::class, $secondaryUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compte secondaire modifié avec succès.');
            return $this->redirectToRoute('app_secondary_list');
        }

        return $this->render('secondary_user/edit.html.twig', [
            'form' => $form->createView(),
            'secondaryUser' => $secondaryUser,
        ]);
    }
}
```

### 3. Point crucial : La sécurité avec un Voter

Pour une gestion vraiment professionnelle et centralisée des permissions, créez un Voter :

```bash
php bin/console make:voter SecondaryUserVoter
```

```php
// src/Security/Voter/SecondaryUserVoter.php
class SecondaryUserVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // On ne vote que pour les utilisateurs et pour nos attributs
        return $subject instanceof User 
            && in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        
        // Si l'utilisateur n'est pas connecté
        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var User $targetUser */
        $targetUser = $subject;

        // Règle d'or : le propriétaire du compte secondaire est son primaryAccount
        return match($attribute) {
            self::VIEW => $this->canView($targetUser, $currentUser),
            self::EDIT, self::DELETE => $this->canModify($targetUser, $currentUser),
            default => false,
        };
    }

    private function canView(User $targetUser, User $currentUser): bool
    {
        // Peut voir si :
        // 1. C'est son propre compte
        // 2. C'est un secondaire qui lui appartient
        // 3. C'est son compte principal (si on est secondaire)
        return $targetUser === $currentUser
            || $targetUser->getPrimaryAccount() === $currentUser
            || $currentUser->getPrimaryAccount() === $targetUser;
    }

    private function canModify(User $targetUser, User $currentUser): bool
    {
        // Seul le compte principal peut modifier/supprimer ses secondaires
        return $targetUser->getPrimaryAccount() === $currentUser;
    }
}
```

Puis dans vos contrôleurs :

```php
// Utilisation du Voter
#[Route('/{id}/edit', name: 'app_secondary_edit')]
public function edit(User $secondaryUser, Request $request, EntityManagerInterface $em): Response
{
    // Une seule ligne pour toute la sécurité !
    $this->denyAccessUnlessGranted('EDIT', $secondaryUser);
    
    // Le reste du code...
}
```

### 4. Dans vos templates Twig

```twig
{# templates/secondary_user/list.html.twig #}
{% extends 'base.html.twig' %}

{% block body %}
    <h1>Mes comptes secondaires</h1>
    
    <a href="{{ path('app_secondary_create') }}" class="btn btn-primary">
        Créer un nouveau compte secondaire
    </a>

    <table class="table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Nom</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {% for secondary in secondaryAccounts %}
                <tr>
                    <td>{{ secondary.email }}</td>
                    <td>{{ secondary.fullName }}</td>
                    <td>{{ secondary.createdAt|date('d/m/Y') }}</td>
                    <td>
                        {% if is_granted('EDIT', secondary) %}
                            <a href="{{ path('app_secondary_edit', {'id': secondary.id}) }}" 
                               class="btn btn-sm btn-warning">
                                Modifier
                            </a>
                        {% endif %}
                        
                        {% if is_granted('DELETE', secondary) %}
                            <form method="post" 
                                  action="{{ path('app_secondary_delete', {'id': secondary.id}) }}" 
                                  style="display: inline-block;"
                                  onsubmit="return confirm('Êtes-vous sûr ?');">
                                <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ secondary.id) }}">
                                <button class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        {% endif %}
                    </td>
                </tr>
            {% else %}
                <tr>
                    <td colspan="4">Aucun compte secondaire trouvé</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
{% endblock %}
```

## 🎯 Points de contrôle essentiels

### ✅ À VÉRIFIER ABSOLUMENT :

1. **Dans security.yaml**, assurez-vous que tout le monde peut accéder à la page de login :
```yaml
access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/register, roles: PUBLIC_ACCESS }
    - { path: ^/secondary-users, roles: ROLE_USER }  # Protégé par authentification
```

2. **Dans le formulaire d'inscription**, assurez-vous qu'on ne peut PAS choisir d'être secondaire :
```php
// Dans RegistrationController
$user = new User();
$user->setPrimaryAccount(null); // Forcément principal à l'inscription
```

3. **Dans le formulaire de création secondaire**, retirez la possibilité de choisir le primaryAccount :
```php
// Ne PAS ajouter de champ pour primaryAccount dans le formulaire
// C'est le contrôleur qui le définit automatiquement avec l'utilisateur connecté
```

## 📊 Résumé des protections

| Niveau de protection | Mécanisme | Ce qu'il garantit |
|---------------------|-----------|-------------------|
| **Interface** | Menu visible uniquement pour les principaux | Un secondaire ne voit même pas le lien "Créer" |
| **Contrôleur** | Vérification `isPrimaryAccount()` | Bloque les accès directs par URL |
| **Base de données** | Clé étrangère `primaryAccount_id` | Intégrité référentielle |
| **Voter** | Vérification `getPrimaryAccount() === $currentUser` | Empêche Jean de modifier les secondaires de Marie |
| **Twig** | `is_granted()` | N'affiche les boutons qu'aux personnes autorisées |

## 💡 En conclusion

**Oui, cette solution supporte parfaitement plusieurs utilisateurs principaux**, chacun gérant ses propres comptes secondaires de manière totalement isolée. La clé est de :

1. **Bien configurer la relation** (ManyToOne/OneToMany réflexive)
2. **Toujours vérifier l'appartenance** dans les contrôleurs
3. **Utiliser un Voter** pour centraliser les règles de sécurité
4. **Ne jamais faire confiance à l'utilisateur** pour lier un compte secondaire au bon principal

C'est exactement comme si chaque utilisateur principal avait son propre "groupe" privé, sans que les groupes se mélangent. 🚀

Des questions sur un point spécifique ?
