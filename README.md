

To run on local machine

Git clone
Cd project
Composer install
npm install
npm run dev

docker build -t mediflow-local .

docker run -d -p 8000:80 --name mediflow-local mediflow-local

docker logs -f mediflow-local


xdg-open http://localhost:8000




