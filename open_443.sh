#!/bin/bash
TOKEN=$(curl -s -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600")
REGION=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/placement/region)
INSTANCE_ID=$(curl -s -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-id)

echo "Region: $REGION"
echo "Instance: $INSTANCE_ID"

SG=$(aws ec2 describe-instances \
    --instance-ids "$INSTANCE_ID" \
    --region "$REGION" \
    --query 'Reservations[0].Instances[0].SecurityGroups[0].GroupId' \
    --output text)

echo "Security Group: $SG"

echo "Abriendo puerto 443..."
aws ec2 authorize-security-group-ingress \
    --group-id "$SG" \
    --protocol tcp \
    --port 443 \
    --cidr 0.0.0.0/0 \
    --region "$REGION" 2>&1

echo "Verificando nginx..."
sudo nginx -t 2>&1
sudo systemctl status nginx --no-pager 2>&1 | head -15
echo "LISTO"
