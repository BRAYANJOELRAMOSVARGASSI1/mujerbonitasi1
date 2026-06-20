#!/bin/bash
TOKEN=$(curl -sf -X PUT "http://169.254.169.254/latest/api/token" -H "X-aws-ec2-metadata-token-ttl-seconds: 21600" 2>/dev/null)
INSTANCE_ID=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/instance-id 2>/dev/null)
echo "Instance ID: $INSTANCE_ID"

# Get the actual sg-xxxx ID from the mac
MAC=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" http://169.254.169.254/latest/meta-data/network/interfaces/macs/ 2>/dev/null | head -1 | tr -d '/')
SG_IDS=$(curl -sf -H "X-aws-ec2-metadata-token: $TOKEN" "http://169.254.169.254/latest/meta-data/network/interfaces/macs/$MAC/security-group-ids" 2>/dev/null)
echo "Security Group IDs: $SG_IDS"
