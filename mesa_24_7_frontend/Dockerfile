FROM node:18-alpine AS build

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm install npm-run-all --omit=dev && npm install

COPY . .

RUN npm run build


FROM nginx:stable-alpine


COPY --from=build /app/dist/spa /usr/share/nginx/html

COPY nginx.conf /etc/nginx/conf.d/default.conf


EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
