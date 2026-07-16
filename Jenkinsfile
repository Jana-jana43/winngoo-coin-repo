pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main',
                    credentialsId: 'github-ssh',
                    url: 'git@github.com:Jana-jana43/winngoo-coin-repo.git'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                ssh -i /var/lib/jenkins/.ssh/id_ed25519 ubuntu@172.31.43.143 << 'EOF'
                cd /var/www/newproject.com

                if [ ! -d .git ]; then
                    git clone git@github.com:Jana-jana43/winngoo-coin-repo.git .
                else
                    git pull origin main
                fi
                EOF
                '''
            }
        }
    }
}
