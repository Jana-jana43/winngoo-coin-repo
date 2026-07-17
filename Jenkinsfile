pipeline {
    agent any

    stages {
        stage('Deploy to Production') {
            steps {
                sh '''
                ssh -i /var/lib/jenkins/.ssh/deploy_ed25519 ubuntu@172.31.43.143 << 'EOF'

                cd /var/www/winngoomodel.com

                git pull origin main

                composer install --no-interaction --prefer-dist

                php artisan migrate --force

                php artisan optimize:clear

                php artisan optimize

                EOF
                '''
            }
        }
    }
}
