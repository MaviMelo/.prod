cat > ./configurar_firewall.sh << 'EOF'
#!/bin/bash
# Script de configuração do firewall para o Metasploitable
# Uso: chmod +x iptables_script.sh && iptables_script.sh

echo "=== Configurando políticas restritivas ==="
iptables -F
iptables -X
iptables -t nat -F
iptables -t mangle -F

iptables -P INPUT DROP
iptables -P FORWARD DROP
iptables -P OUTPUT DROP

echo "=== Regras básicas (loopback e conexões estabelecidas) ==="
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

echo "=== Liberando portas WEB (80, 443, 8080) ==="
iptables -A INPUT -p tcp --dport 80 -m state --state NEW -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -m state --state NEW -j ACCEPT
iptables -A INPUT -p tcp --dport 8080 -m state --state NEW -j ACCEPT

# (Opcional) Descomente as linhas abaixo se quiser permitir DNS e ping
# echo "=== Permissões opcionais: DNS e ping ==="
# iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
# iptables -A INPUT -p udp --sport 53 -m state --state ESTABLISHED -j ACCEPT
# iptables -A OUTPUT -p tcp --dport 53 -j ACCEPT
# iptables -A INPUT -p tcp --sport 53 -m state --state ESTABLISHED -j ACCEPT
# iptables -A INPUT -p icmp --icmp-type echo-request -j ACCEPT
# iptables -A OUTPUT -p icmp --icmp-type echo-reply -j ACCEPT

echo "=== Salvando as regras em /etc/iptables.rules ==="
iptables-save > ./iptables.rules

EFO	
