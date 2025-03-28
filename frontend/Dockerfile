# Use the official Node.js image
FROM node:20-alpine AS build-stage

# Set the working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./

#install
RUN npm install

# Copy the rest of the frontend files
COPY . .

# Build the Vue.js app
RUN npm run build

FROM php:8.2-apache AS production-stage

COPY --from=build-stage /app/dist /var/www/html/ims/frontend

COPY .htaccess /var/www/html/ims/frontend/

COPY ./site-available/frontend.conf /etc/apache2/sites-available/

RUN a2enmod rewrite

RUN a2ensite frontend.conf

RUN a2dissite 000-default.conf

# Expose the default port for the Vue.js app
EXPOSE 80

# Serve the Vue.js app (optional: can use a simple static server for production)
#CMD ["npm", "run", "dev "]
