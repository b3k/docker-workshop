---
# try also 'default' to start simple
theme: seriph
# random image from a curated Unsplash collection by Anthony
# like them? see https://unsplash.com/collections/94734566/slidev
background: https://source.unsplash.com/collection/94734566/1920x1080
# apply any windi css classes to the current slide
class: 'text-center'
# https://sli.dev/custom/highlighters.html
highlighter: shiki
# show line numbers in code blocks
lineNumbers: false
# some information about the slides, markdown enabled
info: |
  ## Slidev Starter Template
  Presentation slides for developers.

  Learn more at [Sli.dev](https://sli.dev)
---

# Docker w GAMIVO

Warsztaty z podstaw oraz praktycznych przypadków uzycia w dewelopmencie GAMIVO.

<img class="w-200 px-50 opacity-70"
  src="https://assets-cf.gamivo.com/assets/images/g-logo.svg"
/>

<div class="pt-12">
  <span @click="$slidev.nav.next" class="px-2 py-1 rounded cursor-pointer" hover="bg-white bg-opacity-10">
    Zaczynamy <carbon:arrow-right class="inline"/>
  </span>
</div>


---

# Czym jest Docker - architektura

<div grid="~ cols-2 gap-2" m="-t-2">
<img src="/architecture.svg" class="h-60 rounded shadow" />

- 👹 **The Docker daemon** - Demon dokera (`dockerd`)który słucha na żądania API oraz zarządza wszystkimi obiektami typu obrazy, kontenery, sieci, wolumeny.
- 😇 **The Docker client** - Najczęstsza metoda interakcji z Dockerem, czyli np. użycie `docker run` to tak naprwade wysłanie żądania do API demona.
- 🤹 **Docker registries** - Rejestr(`registry`) który przechowuje obrazy dockera, np. https://hub.docker.com/ jest publicznym rejestrem Dockera, a sam Docker jest skonfigurowany aby domyślnie pobierać obrazy z hub.docker.com. 

</div>
<!--
You can have `style` tag in markdown to override the style for the current page.
Learn more: https://sli.dev/guide/syntax#embedded-styles
-->

---

# Czym jest Docker - architektura cz. 2 - obiekty

<div grid="~ cols-2 gap-2" m="-t-2">
<img src="/architecture.svg" class="h-60 rounded shadow" />

- 📼 **Images** - Obraz to zestaw instrukcji uzywany podczas tworzenia kontenera (`containers`).  Często obraz jest bazowany na innym obrazie, np. obrazy GAMIVO backend bazują na obrazie `Alpine` gdzie dogrywane są tylko brakujące elementy w postaci biliotek i nnych narzędzi.
- 📦 **Containers** - Kontener to uruchomiona instancja obrazu. Możesz tworzyć, uruchamiać, zatrzymywać, przenosić albo usuwać kontenery używając klienta Dockera. Możesz podłączyć kontener do sieci, podczepić `storage` lub zbudować obraz bazujący na aktualnym stanie kontenera.


</div>
---

# Jak działają kontenery

<div>Mówiąc najprościej, kontener to po prostu kolejny proces na twoim komputerze, który został odizolowany od wszystkich innych procesów na komputerze hosta. Ta izolacja wykorzystuje Kernelowe `namesapces` i `cgroups`, funkcje, które były w Linuksie od dawna (namesapces od 2004, od wersji kernela 2.4).</div>

Super artykuł opisujący każdy szczegół:

https://medium.com/@saschagrunert/demystifying-containers-part-i-kernel-space-2c53d6979504

---

# The Docker deamon REST API

```bash
curl --unix-socket /var/run/docker.sock http://localhost/v1.40/containers/json | jq
```
<small>czasem trzeba uzyć `http:/localhost/v1.40/containers/json`</small>


---

# Docker run - podstawy

Uruchamiamy kontener:

```bash
docker run hello-world
```

1. Jeśli nie miałeś obrazu hello-world został on pobrany z hub.docker.com (`docker pull hello-world`)
2. Docker klient wysłał żądanie uwtorzenia kontenera hello-world (`docker container create hello-world`)
3. Docker alokuje system plików dla lokalnego użycia wew. kontenera (`mount -l | grep docker/containers`)
4. Docker podpina kontener do domyslnej sieci (chyba że zdeifiniowano inaczej)
5. Wykonywana jest instrukcja z CMD lub ENTRYPOINT

---

# Docker run - podstawy cz. 2

```bash
docker pull busybox
```


```bash
docker images | grep busybox
```

```bash
docker run busybox
```

```bash
docker run busybox echo "hello from busybox"
```

```bash
docker run -dit --name verybusy busybox sh
```

```bash
docker ps | grep verybusy
```

```bash
docker attach verybusy
exit
```

```bash
docker ps | grep verybusy
```

---

# Docker run - podstawy cz. 3

```bash
docker run -it --name verybusy2 busybox sh

rm -rf /bin
ls -la
exit
```

```bash
docker run -it --name verybusy2 busybox sh

ls -la
exit
```

```bash
docker ps -a -f status=exited
```

```
docker rm verybusy2 verybusy
```

```
docker run -itd --name verybusy2 busybox sh
docker ps
docker stop verybusy2
docker rm verybusy2
```


---

# Docker run - podstawy cz. 4


```bash
docker run -d -P --name static-site prakhar1989/static-site
docker ps | grep static-site
```

```
ae552a614f55        prakhar1989/static-site      "./wrapper.sh"           3 seconds ago       Up 2 seconds        0.0.0.0:32771->80/tcp, 0.0.0.0:32770->443/tcp       static-site
```
```bash
xdg-open 0.0.0.0:32771
```

```
docker port static-site
```
```
443/tcp -> 0.0.0.0:32770
80/tcp -> 0.0.0.0:32771
```

```
docker run -dp 8888:80 --name="szalony-kontener" prakhar1989/static-site
xdg-open 0.0.0.0:8888
docker pause szalony-kontener
xdg-open 0.0.0.0:8888
docker unpause szalony-kontener
docker rm -f szalony-kontener
```

---

# Docker run - podstawy cz. 5 - pytania

1. Proszę usunąć uruchomiony kontener `static-site`
2. Proszę uruchomić obraz `prakhar1989/static-site` który będzie mapował port hosta `9999` na lokalny kontenera `80`
3. Jaka jest różnica między kontenerem a obrazem ?


---

# Dockerfile - podstawy

```bash
docker build -f Dockerfile-php -t gamivo/php-simple php-context-1
docker image inspect gamivo/php-simple:latest | jq
docker run -p 8888:80 gamivo/php-simple
xdg-open 0.0.0.0:8888
[Ctrl+c]
```

```
docker run -itp 8888:80 gamivo/php-simple bash
xdg-open 0.0.0.0:8888
apt install vim -y
vim index.php
```

zmieniamy treść:

```php
<?php
die("not this time");
phpinfo();
```
wychodzimy z vim :-)
```bash
[Esc]:wq
```
---

# Dockerfile - podstawy cz. 2

w kontenerze
```bash
php -S 0.0.0.0:80 -t ./
```

odświeżamy http://0.0.0.0:8888

```
docker run -p 8888:80 gamivo/php-simple
```

<img class="w-30" src="https://media.giphy.com/media/WrxMtxWskefVxPF9L4/giphy.gif">

---

# Dockerfile - podstawy cz. 3


```
# bazowy obraz
FROM php:7.4

# jakie porty moze exposowac kontener
EXPOSE 80

# skopiuj wszystkie pliki z katalogu kontekstu do glownego katalogu (w tym przypadku /)
COPY . .

# zainstaluj wget
RUN apt-get update && apt-get install -y wget

# uruchom instalacje PDO
RUN docker-php-ext-configure pdo_mysql
RUN docker-php-ext-install pdo_mysql

# domyslnie uruchom proces php -S 0.0.0.0:80 -t ./
CMD ["php", "-S", "0.0.0.0:80", "-t", "./"]
```

---

# Docker network - podstawy

```bash
docker network create workshop
docker run --net workshop --name hebel1 -d gamivo/php-simple:latest
docker run --net workshop --name hebel2 -d gamivo/php-simple:latest
docker exec -it hebel1 bash
curl hebel2
exit

docker exec -it hebel2 bash
curl hebel1
exit
```
