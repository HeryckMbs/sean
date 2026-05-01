# Perfil Digital Ads

Landing institucional com painel administrativo em Laravel, Blade, PostgreSQL, Docker, Nginx e PHP-FPM.

## Stack

- Laravel monolítico
- Blade + Materialize CSS via CDN
- PostgreSQL
- Docker Compose
- Nginx + PHP-FPM
- Upload local em `storage/app/public`
- Sem Redis, API separada, React ou Vue

## Subir localmente

```bash
docker compose up -d --build
```

Site público:

```txt
http://localhost:5555
```

Painel admin:

```txt
http://localhost:5555/admin
```

Credenciais seed:

```txt
admin@perfildigitalads.com.br
Veja ADMIN_PASSWORD no .env
```

## Publicar para teste em servidor

1. Ajuste o `.env` antes de subir:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP_DO_SERVIDOR:5555
APP_PORT=5555
ADMIN_EMAIL=admin@perfildigitalads.com.br
ADMIN_PASSWORD=senha-forte-para-o-cliente
SEED_ON_FIRST_BOOT=true
```

2. Suba os containers:

```bash
docker compose up -d --build
```

O container da aplicação roda `migrate --force`, cria o link de storage e executa o seed somente no primeiro boot do volume, quando `SEED_ON_FIRST_BOOT=true`. Depois que o teste estiver criado, mantenha esse valor como `false` se quiser impedir qualquer seed automático em um novo volume.

3. Libere a porta no servidor, se o firewall estiver ativo:

```bash
sudo ufw allow 5555/tcp
```

URLs para o cliente:

```txt
Site: http://IP_DO_SERVIDOR:5555
Admin: http://IP_DO_SERVIDOR:5555/admin
```

## Conteúdo editável

O painel permite editar configurações gerais, textos das seções, serviços, benefícios, nichos, etapas do processo, cases, depoimentos, FAQ, redes sociais e leads recebidos.

O campo `lead_webhook_url` fica salvo nas configurações e em cada lead para integração futura com webhook, CRM ou Google Sheets.
