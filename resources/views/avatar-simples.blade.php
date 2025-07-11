@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Avatar 3D - Ana</h4>
                </div>
                <div class="card-body">
                    <!-- Container do Avatar 3D -->
                    <div class="avatar-container">
                        <div id="scene-container" class="scene-container"></div>
                        
                        <!-- Controles do Avatar -->
                        <div class="avatar-controls">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" onclick="playAnimation('idle')">
                                    <i class="fas fa-play"></i> Idle
                                </button>
                                <button type="button" class="btn btn-success" onclick="playAnimation('talk')">
                                    <i class="fas fa-comment"></i> Falar
                                </button>
                                <button type="button" class="btn btn-info" onclick="playAnimation('wave')">
                                    <i class="fas fa-hand-paper"></i> Acenar
                                </button>
                                <button type="button" class="btn btn-warning" onclick="playAnimation('gesture')">
                                    <i class="fas fa-hands"></i> Gesto
                                </button>
                                <button type="button" class="btn btn-danger" onclick="stopAnimation()">
                                    <i class="fas fa-stop"></i> Parar
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetAvatar()">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-container {
        position: relative;
        width: 100%;
        height: 70vh;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .scene-container {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .scene-container canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    .avatar-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 15px;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .btn-group .btn {
        margin: 0 5px;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .loading-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #666;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .status-info {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/three@0.152.2/build/three.min.js"></script>
<script src="https://unpkg.com/three@0.152.2/examples/js/loaders/GLTFLoader.js"></script>

<script>
    let scene, camera, renderer, avatar, mixer, clock;
    let isAnimating = false;
    let currentAnimation = null;
    let avatarLoaded = false;

    function showStatus(message, isError = false) {
        const container = document.getElementById('scene-container');
        let statusDiv = container.querySelector('.status-info');
        
        if (!statusDiv) {
            statusDiv = document.createElement('div');
            statusDiv.className = 'status-info';
            container.appendChild(statusDiv);
        }
        
        statusDiv.textContent = message;
        statusDiv.style.background = isError ? 'rgba(220, 53, 69, 0.8)' : 'rgba(0, 123, 255, 0.8)';
        
        console.log('Avatar Status:', message);
    }

    function showLoading(message = 'Carregando...') {
        const container = document.getElementById('scene-container');
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p>${message}</p>
            </div>
        `;
        console.log('Avatar:', message);
    }

    function tryLoadGLB() {
        showStatus('Tentando carregar modelo GLB...');
        
        if (typeof THREE === 'undefined' || typeof THREE.GLTFLoader === 'undefined') {
            showStatus('Three.js/GLTFLoader não disponível', true);
            setTimeout(createProceduralAvatar, 1000);
            return;
        }

        const loader = new THREE.GLTFLoader();
        const glbPaths = [
            '/models/avatar_ana.glb',
            './models/avatar_ana.glb',
            '../models/avatar_ana.glb'
        ];
        
        let currentPathIndex = 0;
        
        function tryNextPath() {
            if (currentPathIndex >= glbPaths.length) {
                showStatus('Modelo GLB não encontrado, usando procedural', true);
                setTimeout(createProceduralAvatar, 1000);
                return;
            }
            
            const currentPath = glbPaths[currentPathIndex];
            showStatus(`Tentando: ${currentPath}`);
            
            loader.load(
                currentPath,
                function(gltf) {
                    showStatus('Modelo GLB carregado com sucesso!');
                    console.log('GLB carregado:', gltf);
                    
                    avatar = gltf.scene;
                    avatar.scale.set(1, 1, 1);
                    avatar.position.set(0, -1, 0);
                    
                    if (gltf.animations && gltf.animations.length > 0) {
                        mixer = new THREE.AnimationMixer(avatar);
                        console.log('Animações GLB encontradas:', gltf.animations.length);
                    }
                    
                    scene.add(avatar);
                    avatarLoaded = true;
                    showStatus('Avatar GLB ativo');
                },
                function(progress) {
                    const percent = Math.round((progress.loaded / progress.total) * 100);
                    showStatus(`Carregando GLB: ${percent}%`);
                },
                function(error) {
                    console.error('Erro ao carregar', currentPath, ':', error);
                    currentPathIndex++;
                    setTimeout(tryNextPath, 500);
                }
            );
        }
        
        tryNextPath();
    }

    function createProceduralAvatar() {
        showStatus('Criando avatar procedural...');
        console.log('Criando avatar 3D procedural...');
        
        const group = new THREE.Group();
        
        // Materiais
        const skinMaterial = new THREE.MeshLambertMaterial({ color: 0xffdbac });
        const hairMaterial = new THREE.MeshLambertMaterial({ color: 0x8B4513 });
        const clothMaterial = new THREE.MeshLambertMaterial({ color: 0x4287f5 });
        const eyeMaterial = new THREE.MeshBasicMaterial({ color: 0x000000 });
        
        // Cabeça
        const headGeometry = new THREE.SphereGeometry(0.35, 32, 32);
        const head = new THREE.Mesh(headGeometry, skinMaterial);
        head.position.y = 1.6;
        head.name = 'head';
        group.add(head);
        
        // Cabelo
        const hairGeometry = new THREE.SphereGeometry(0.37, 32, 32, 0, Math.PI * 2, 0, Math.PI * 0.7);
        const hair = new THREE.Mesh(hairGeometry, hairMaterial);
        hair.position.y = 1.75;
        group.add(hair);
        
        // Olhos
        const eyeGeometry = new THREE.SphereGeometry(0.05, 16, 16);
        const leftEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
        leftEye.position.set(-0.1, 1.65, 0.3);
        group.add(leftEye);
        
        const rightEye = new THREE.Mesh(eyeGeometry, eyeMaterial);
        rightEye.position.set(0.1, 1.65, 0.3);
        group.add(rightEye);
        
        // Corpo (simplificado para exemplo)
        const bodyGeometry = new THREE.CylinderGeometry(0.25, 0.35, 1.2, 16);
        const body = new THREE.Mesh(bodyGeometry, clothMaterial);
        body.position.y = 0.4;
        body.name = 'body';
        group.add(body);
        
        // Braços
        const armGeometry = new THREE.CylinderGeometry(0.08, 0.1, 0.8, 16);
        
        const leftArm = new THREE.Mesh(armGeometry, clothMaterial);
        leftArm.position.set(-0.4, 0.6, 0);
        leftArm.rotation.z = 0.3;
        leftArm.name = 'leftArm';
        group.add(leftArm);
        
        const rightArm = new THREE.Mesh(armGeometry, clothMaterial);
        rightArm.position.set(0.4, 0.6, 0);
        rightArm.rotation.z = -0.3;
        rightArm.name = 'rightArm';
        group.add(rightArm);
        
        avatar = group;
        scene.add(avatar);
        avatarLoaded = true;
        showStatus('Avatar procedural ativo');
        
        console.log('Avatar procedural criado com sucesso!');
    }

    function initAvatar() {
        console.log('Inicializando sistema de avatar...');
        const container = document.getElementById('scene-container');
        
        if (!container) {
            console.error('Container não encontrado!');
            return;
        }
        
        showLoading('Inicializando Three.js...');
        
        // Verificar dependências
        if (typeof THREE === 'undefined') {
            showStatus('Three.js não carregado!', true);
            container.innerHTML = '<div class="loading-container"><p style="color: red;">Erro: Three.js não carregado</p></div>';
            return;
        }
        
        setTimeout(() => {
            try {
                container.innerHTML = '';
                
                // Configurar Three.js
                scene = new THREE.Scene();
                scene.background = new THREE.Color(0xf5f7fa);
                
                camera = new THREE.PerspectiveCamera(60, container.clientWidth / container.clientHeight, 0.1, 1000);
                camera.position.set(0, 1.5, 3);
                camera.lookAt(0, 1, 0);
                
                renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
                renderer.setSize(container.clientWidth, container.clientHeight);
                renderer.shadowMap.enabled = true;
                renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                container.appendChild(renderer.domElement);
                
                clock = new THREE.Clock();
                
                // Luzes
                const ambient = new THREE.AmbientLight(0xffffff, 0.6);
                scene.add(ambient);
                
                const directional = new THREE.DirectionalLight(0xffffff, 0.8);
                directional.position.set(5, 10, 5);
                directional.castShadow = true;
                scene.add(directional);
                
                // Eventos
                window.addEventListener('resize', onWindowResize);
                
                // Começar animação
                animate();
                
                // Tentar carregar GLB, se falhar usar procedural
                tryLoadGLB();
                
                console.log('Sistema inicializado com sucesso!');
                
            } catch (error) {
                console.error('Erro na inicialização:', error);
                showStatus('Erro na inicialização: ' + error.message, true);
            }
        }, 500);
    }
    
    function onWindowResize() {
        const container = document.getElementById('scene-container');
        if (!container || !camera || !renderer) return;
        
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    }
    
    function animate() {
        requestAnimationFrame(animate);
        
        const delta = clock.getDelta();
        
        if (mixer) {
            mixer.update(delta);
        }
        
        // Rotação suave quando não há animação específica
        if (avatar && avatarLoaded && !isAnimating) {
            avatar.rotation.y += 0.005;
        }
        
        if (renderer && scene && camera) {
            renderer.render(scene, camera);
        }
    }

    // Funções de animação
    function playAnimation(animationName) {
        if (!avatarLoaded) {
            showStatus('Avatar ainda não carregado', true);
            return;
        }
        
        console.log('Reproduzindo animação:', animationName);
        isAnimating = true;
        currentAnimation = animationName;
        
        stopAnimation();
        
        switch(animationName) {
            case 'idle':
                idleAnimation();
                break;
            case 'talk':
                talkAnimation();
                break;
            case 'wave':
                waveAnimation();
                break;
            case 'gesture':
                gestureAnimation();
                break;
        }
        
        showStatus(`Animação: ${animationName}`);
    }
    
    function stopAnimation() {
        isAnimating = false;
        currentAnimation = null;
        showStatus('Avatar em modo livre');
    }
    
    function resetAvatar() {
        stopAnimation();
        if (avatar) {
            avatar.position.set(0, avatar.position.y, 0);
            avatar.rotation.set(0, 0, 0);
        }
        showStatus('Avatar resetado');
    }

    // Animações básicas
    function idleAnimation() {
        if (!avatar) return;
        
        let time = 0;
        const animate = () => {
            if (currentAnimation !== 'idle') return;
            
            time += 0.05;
            
            const head = avatar.getObjectByName ? avatar.getObjectByName('head') : null;
            if (head) {
                head.rotation.y = Math.sin(time) * 0.1;
                head.rotation.x = Math.sin(time * 0.7) * 0.05;
            }
            
            setTimeout(() => requestAnimationFrame(animate), 50);
        };
        animate();
    }
    
    function talkAnimation() {
        if (!avatar) return;
        
        let time = 0;
        const animate = () => {
            if (currentAnimation !== 'talk') return;
            
            time += 0.1;
            
            const head = avatar.getObjectByName ? avatar.getObjectByName('head') : null;
            if (head) {
                head.rotation.x = Math.sin(time * 3) * 0.05;
                head.rotation.y = Math.sin(time * 2) * 0.1;
            }
            
            setTimeout(() => requestAnimationFrame(animate), 30);
        };
        animate();
    }
    
    function waveAnimation() {
        if (!avatar) return;
        
        let time = 0;
        const animate = () => {
            if (currentAnimation !== 'wave') return;
            
            time += 0.15;
            
            const rightArm = avatar.getObjectByName ? avatar.getObjectByName('rightArm') : null;
            if (rightArm) {
                rightArm.rotation.z = -0.5 + Math.sin(time * 2) * 0.4;
                rightArm.rotation.x = Math.sin(time) * 0.2;
            }
            
            setTimeout(() => requestAnimationFrame(animate), 40);
        };
        animate();
    }
    
    function gestureAnimation() {
        if (!avatar) return;
        
        let time = 0;
        const animate = () => {
            if (currentAnimation !== 'gesture') return;
            
            time += 0.1;
            
            const leftArm = avatar.getObjectByName ? avatar.getObjectByName('leftArm') : null;
            const rightArm = avatar.getObjectByName ? avatar.getObjectByName('rightArm') : null;
            
            if (leftArm) {
                leftArm.rotation.z = 0.5 + Math.sin(time * 2) * 0.4;
            }
            
            if (rightArm) {
                rightArm.rotation.z = -0.5 + Math.sin(time * 2 + 1) * 0.4;
            }
            
            setTimeout(() => requestAnimationFrame(animate), 50);
        };
        animate();
    }

    // Inicializar quando DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM carregado, inicializando avatar...');
        setTimeout(initAvatar, 1000); // Aguardar bibliotecas carregarem
    });
</script>
@endsection
