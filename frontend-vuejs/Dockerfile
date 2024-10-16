# Étape 1 : Utiliser l'image Node.js pour construire l'application
FROM node:16.10.0-alpine3.13 AS build-stage

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances du projet
RUN npm install

# Copier le reste des fichiers du projet dans le conteneur
COPY . .

# Construire l'application Vue.js pour le mode production dans le dossier dist
RUN npm run build

# Étape 2 : Utiliser l'image Nginx légère pour le conteneur de production
FROM nginx:stable-alpine AS production-stage

# Copier l'application construite depuis l'étape précédente vers le conteneur Nginx
COPY --from=build-stage /app/dist /usr/share/nginx/html

# Copier le fichier de configuration Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Exposer le port 80
EXPOSE 80

# Démarrer Nginx pour servir l'application
CMD ["nginx", "-g", "daemon off;"]