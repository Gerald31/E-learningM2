# E-learningDevopsFinal
# E-learningDevopsFinal
# E-learningMasterFinal
# E-learningM2
name: Deploy to AWS EKS

on:
  push:
    branches:
      - main  # Déploie uniquement sur la branche 'main'

jobs:
  build-and-deploy:
    runs-on: ubuntu-latest

    steps:
    # Étape 1 : Checkout du dépôt
    - name: Checkout repository
      uses: actions/checkout@v3

    # Étape 2 : Configuration AWS (Assure-toi que les secrets AWS_ACCESS_KEY_ID et AWS_SECRET_ACCESS_KEY sont configurés)
    - name: Configure AWS credentials
      uses: aws-actions/configure-aws-credentials@v1
      with:
        aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
        aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
        aws-region: af-south-1

    # Étape 3 : Login to Amazon ECR
    - name: Login to Amazon ECR
      id: login-ecr
      uses: aws-actions/amazon-ecr-login@v2

    # Étape 4 : Créer le dépôt ECR s'il n'existe pas
    - name: Create ECR repository if not exists
      run: |
        aws ecr describe-repositories --repository-names projet-master || \
        aws ecr create-repository --repository-name projet-master --region af-south-1

    # Étape 5 : Build and Push Docker Image
    - name: Build, tag, and push docker image to Amazon ECR
      env:
        REGISTRY: ${{ steps.login-ecr.outputs.registry }}
        REPOSITORY: projet-master
        IMAGE_TAG: v1.0.0
      run: |
        docker build -t $REGISTRY/$REPOSITORY:$IMAGE_TAG .
        docker push $REGISTRY/$REPOSITORY:$IMAGE_TAG

    # Étape 6 : Mettre à jour la configuration du cluster EKS
    - name: Update kube config
      run: aws eks update-kubeconfig --name projet-master-eks

    # Étape 7 : Créer ou mettre à jour le namespace et déployer les manifests
    - name: Create namespace if not exists
      run: |
        kubectl get namespace productions || kubectl create namespace productions
        kubectl config set-context --current --namespace=productions

    - name: Apply Kubernetes manifests
      run: |
        kubectl apply -f production/secrets.yaml
        kubectl apply -f production/prod-aws-master.yaml
      
    - name: Force restart pods
      run: kubectl rollout restart deployment/projet-master
