#!/bin/bash

echo "=== TEST CANDIDATURE SYSTÈME ==="

# 1. Se connecter et récupérer le token CSRF
echo "1. Connexion et récupération du token..."
COOKIE_JAR="/tmp/cookies.txt"
rm -f $COOKIE_JAR

# Récupérer la page de login pour obtenir le token CSRF
LOGIN_PAGE=$(curl -s -c $COOKIE_JAR http://127.0.0.1:8001/login)
CSRF_TOKEN=$(echo "$LOGIN_PAGE" | grep -o 'name="_token" value="[^"]*"' | sed 's/name="_token" value="//;s/"//')

if [ -z "$CSRF_TOKEN" ]; then
    echo "ERREUR: Impossible de récupérer le token CSRF"
    exit 1
fi

echo "Token CSRF récupéré: ${CSRF_TOKEN:0:20}..."

# 2. Se connecter
echo "2. Connexion avec l'utilisateur étudiant..."
LOGIN_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR \
    -X POST \
    -d "_token=$CSRF_TOKEN" \
    -d "email=etudiant@test.com" \
    -d "password=password" \
    http://127.0.0.1:8001/login)

# 3. Récupérer la page de l'offre pour obtenir un nouveau token CSRF
echo "3. Récupération de la page offre..."
OFFRE_PAGE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR http://127.0.0.1:8001/offres/4)
CSRF_TOKEN_CANDIDATURE=$(echo "$OFFRE_PAGE" | grep -o 'name="_token" value="[^"]*"' | sed 's/name="_token" value="//;s/"//')

if [ -z "$CSRF_TOKEN_CANDIDATURE" ]; then
    echo "ERREUR: Impossible de récupérer le token CSRF pour la candidature"
    exit 1
fi

echo "Token CSRF candidature récupéré: ${CSRF_TOKEN_CANDIDATURE:0:20}..."

# 4. Envoyer la candidature
echo "4. Envoi de la candidature..."
CANDIDATURE_RESPONSE=$(curl -s -b $COOKIE_JAR -c $COOKIE_JAR \
    -X POST \
    -F "_token=$CSRF_TOKEN_CANDIDATURE" \
    -F "offre_id=4" \
    -F "message=Je suis très intéressé par ce stage de développement web chez ROMAS. Test automatique." \
    -F "cv=@/tmp/cv_test.txt" \
    -F "confirmation=1" \
    http://127.0.0.1:8001/candidatures)

echo "5. Réponse du serveur:"
echo "$CANDIDATURE_RESPONSE" | head -20

# 6. Vérifier en base de données
echo "6. Vérification en base de données..."
php artisan tinker --execute="
echo 'Nombre de candidatures: ' . App\Models\Candidature::count() . PHP_EOL;
\$candidature = App\Models\Candidature::latest()->first();
if (\$candidature) {
    echo 'Dernière candidature:' . PHP_EOL;
    echo '- ID: ' . \$candidature->id . PHP_EOL;
    echo '- User ID: ' . \$candidature->user_id . PHP_EOL;
    echo '- Offre ID: ' . \$candidature->offre_id . PHP_EOL;
    echo '- Message: ' . substr(\$candidature->message, 0, 50) . '...' . PHP_EOL;
    echo '- Statut: ' . \$candidature->statut . PHP_EOL;
    echo '- CV: ' . (\$candidature->cv ? 'Présent' : 'Absent') . PHP_EOL;
}
"

# Nettoyer
rm -f $COOKIE_JAR /tmp/cv_test.txt

echo "=== FIN DU TEST ==="
