# Build & Push Laravel Lambda-Compatible Docker Image to AWS ECR

This guide explains how to build the Laravel-compatible Docker image and push it to **Amazon ECR** for use with AWS Lambda.

---

## 1. Authenticate Docker with ECR
Run the following command to authenticate your Docker client with your AWS Elastic Container Registry (ECR):

```bash
aws ecr get-login-password --region ap-south-1 | docker login --username AWS --password-stdin 136003615631.dkr.ecr.ap-south-1.amazonaws.com
```


## 2. Build the Docker Image
Since the Dockerfile is inside deploy/lambda/, run:

```bash
cd /home/parth.nakum/AWS-compatible

docker build -t 136003615631.dkr.ecr.ap-south-1.amazonaws.com/lambda-compatible-image1:latest -f deploy/lambda/Dockerfile .
```

## 3. Verify Image Built Locally
Check the image exists in your local Docker environment:

```bash
docker images | grep lambda-compatible-image1
```

## 4. Push Image to ECR
Push the Docker image to your AWS ECR repository:

```bash
docker push 136003615631.dkr.ecr.ap-south-1.amazonaws.com/lambda-compatible-image1:latest
```
