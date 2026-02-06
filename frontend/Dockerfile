FROM node:25.3 AS base

WORKDIR /app

COPY package.json package-lock.json .
RUN npm ci
COPY . .

# DEV
FROM base AS dev
EXPOSE 5173

CMD ["npm", "run", "dev"]
