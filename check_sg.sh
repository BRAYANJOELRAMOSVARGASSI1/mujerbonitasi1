#!/bin/bash
echo "=== Info de la instancia ==="
TOKEN=$(curl -sf -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600" 2>/dev/null)
if [ -z "$TOKEN" ]; then
    # fallback IMDSv1
    INSTANCE_ID=$(curl -sf http://169.254.169.254/latest/meta-data/instance-id 2>/dev/null)
    SG_NAME=$(curl -sf http://169.254.169.254/latest/meta-data/security-groups 2>/dev/null)
    REGION=$(curl -sf http://169.254.169.254/latest/meta-data/placement/region 2>/dev/null)
else
    INSTANCE_ID=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-id 2>/dev/null)
    SG_NAME=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/security-groups 2>/dev/null)
    REGION=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/region 2>/dev/null)
fi

echo "Instance ID: ${INSTANCE_ID:-NOT_FOUND}"
echo "Security Group: ${SG_NAME:-NOT_FOUND}"
echo "Region: ${REGION:-NOT_FOUND}"

echo ""
echo "=== Verificar si el puerto 443 responde internamente ==="
curl -sk --max-time 3 https://localhost/ -o /dev/null -w "Status HTTPS local: %{http_code}\n" 2>&1

echo ""
echo "=== Puertos abiertos en nginx ==="
sudo ss -tlnp | grep nginx
