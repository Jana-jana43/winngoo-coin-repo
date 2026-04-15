pipeline {
    agent any

    stages {

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t coin-project .'
            }
        }

        stage('Run Container') {
            steps {
                sh '''
                docker stop coin-container || true
                docker rm coin-container || true
                docker run -d -p 80:80 --name coin-container coin-project
                '''
            }
        }

    }
}
