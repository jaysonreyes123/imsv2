# Use the official Node.js image
FROM node:20

# Set the working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy the rest of the frontend files
COPY . .

# Build the Vue.js app
RUN npm run build

# Expose the default port for the Vue.js app
EXPOSE 80

# Serve the Vue.js app (optional: can use a simple static server for production)
CMD ["npm", "run", "dev"]
