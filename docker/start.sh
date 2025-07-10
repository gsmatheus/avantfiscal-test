#!/bin/bash

echo "🚀 Iniciando Sistema de Reservas Avant..."

cd "$(dirname "$0")"

echo "📦 Construindo containers..."
docker compose build

echo "🔄 Iniciando serviços..."
docker compose up -d

echo "⏳ Aguardando banco de dados..."
sleep 10

echo "✅ Sistema iniciado com sucesso!"
echo ""
echo "🌐 Acesse: http://localhost:8050"
echo "📊 Admin: admin@sistema.com / password"
echo ""
echo "📋 Comandos úteis:"
echo "  docker compose logs -f app    # Ver logs da aplicação"
echo "  docker compose logs -f db     # Ver logs do banco"
echo "  docker compose down           # Parar serviços"
echo "  docker compose restart        # Reiniciar serviços" 