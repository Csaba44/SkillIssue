########################
# BASE STAGE
########################
FROM node:25.3-alpine AS base

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .


########################
# DEV STAGE
########################
FROM base AS dev
EXPOSE 3000
CMD ["npm", "run", "dev"]

########################
# PROD STAGE
########################
FROM base AS prod

CMD ["npm", "run", "start"]
