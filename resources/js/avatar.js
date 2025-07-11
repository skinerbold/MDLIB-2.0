import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

// Só executa o código se o container do avatar existir na página
if (document.getElementById('scene-container')) {

    let scene, camera, renderer, avatar, mixer, clock, controls;
    let avatarLoaded = false;

    function showStatus(message, isError = false) {
        const container = document.getElementById('scene-container');
        if (!container) return;

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
        if (!container) return;
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p>${message}</p>
            </div>
        `;
        console.log('Avatar:', message);
    }

    function initScene(model) {
        const container = document.getElementById('scene-container');
        if (!container) return;
        container.innerHTML = ''; // Limpa o loading

        scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf0f0f0);
        
        camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
        
        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true;
        container.appendChild(renderer.domElement);

        controls = new OrbitControls(camera, renderer.domElement);
        
        const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
        scene.add(ambientLight);
        
        const directionalLight = new THREE.DirectionalLight(0xffffff, 1.5);
        directionalLight.position.set(5, 10, 7.5);
        directionalLight.castShadow = true;
        scene.add(directionalLight);

        avatar = model;

        // --- LÓGICA CORRIGIDA DE AJUSTE DE CÂMERA E POSIÇÃO ---
        const box = new THREE.Box3().setFromObject(avatar);
        const size = box.getSize(new THREE.Vector3());
        const center = box.getCenter(new THREE.Vector3());

        // Move o avatar para que seu centro fique na origem da cena (0,0,0)
        avatar.position.sub(center);

        // Aponta os controles de órbita para a nova origem
        controls.target.set(0, 0, 0);

        // Calcula a distância ideal da câmera para enquadrar o objeto
        const maxDim = Math.max(size.x, size.y, size.z);
        const fov = camera.fov * (Math.PI / 180);
        const cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));

        // Ajustar multiplier para garantir que o avatar caiba completamente no container
        const containerAspect = container.clientWidth / container.clientHeight;
        
        // Para o dashboard, usar um multiplier que garanta que o avatar caiba completamente
        let multiplier = 0.8; // Valor mais conservador para garantir que não corte
        
        // Se o container for muito pequeno, afastar mais a câmera
        if (container.clientHeight <= 300) {
            multiplier = 1.0;
        }
        
        // Afasta a câmera um pouco para dar uma margem
        camera.position.set(0, 0, cameraZ * multiplier);
        
        // Atualiza os controles após mover a câmera
        controls.update();

        scene.add(avatar);
        
        avatarLoaded = true;
        showStatus('Avatar carregado com sucesso!', false);

        if (model.animations && model.animations.length) {
            mixer = new THREE.AnimationMixer(avatar);
        } else {
            showStatus('Modelo não contém animações.', false);
        }
        
        clock = new THREE.Clock();
        animate();

        window.addEventListener('resize', onWindowResize, false);
    }

    function tryLoadGLB() {
        showStatus('Iniciando carregamento do modelo GLB...');
        
        const loader = new GLTFLoader();
        const modelUrl = document.querySelector('meta[name="avatar-url"]').getAttribute('content');
        
        if (!modelUrl) {
            showStatus('ERRO: URL do modelo não encontrada.', true);
            return;
        }

        showStatus(`Carregando modelo de: ${modelUrl}`);

        loader.load(
            modelUrl,
            (gltf) => {
                console.log('Modelo GLB carregado com sucesso.', gltf);
                initScene(gltf.scene);
                avatarLoaded = true;
            },
            (xhr) => {
                const percentLoaded = (xhr.loaded / xhr.total) * 100;
                showStatus(`Carregando: ${Math.round(percentLoaded)}%`);
            },
            (error) => {
                console.error('Erro ao carregar o modelo GLB:', error);
                showStatus(`ERRO CRÍTICO AO CARREGAR O MODELO: ${error.message || 'Verifique o console.'}`, true);
                const container = document.getElementById('scene-container');
                if(container) {
                    container.innerHTML = `<div class="loading-container" style="color: red;">Falha ao carregar o avatar. Verifique se o arquivo<br><code>${modelUrl}</code><br>está acessível e sem erros.</div>`;
                }
            }
        );
    }

    function onWindowResize() {
        const container = document.getElementById('scene-container');
        if (!container || !camera || !renderer) return;

        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    }

    function animate() {
        if (!renderer) return;
        requestAnimationFrame(animate);
        const delta = clock.getDelta();
        if (mixer) mixer.update(delta);
        controls.update();
        renderer.render(scene, camera);
    }

    // Funções dos botões de controle (disponibilizadas globalmente)
    window.playAnimation = function(animationName) {
        if (!avatarLoaded || !mixer) {
            console.log('Avatar não carregado ou não possui animações.');
            return;
        }
        
        // Para todas as animações em execução
        mixer.stopAllAction();
        
        // Aqui você pode adicionar lógica específica para cada animação
        // Por enquanto, vamos apenas registrar no console
        console.log(`Tentando reproduzir animação: ${animationName}`);
        showStatus(`Reproduzindo: ${animationName}`);
    };

    window.stopAnimation = function() {
        if (!avatarLoaded || !mixer) {
            console.log('Avatar não carregado ou não possui animações.');
            return;
        }
        
        mixer.stopAllAction();
        showStatus('Animações paradas.');
        console.log('Todas as animações foram paradas.');
    };

    window.resetAvatar = function() {
        if (!avatarLoaded || !avatar) {
            console.log('Avatar não carregado.');
            return;
        }
        
        // Reset da posição e rotação
        avatar.rotation.set(0, 0, 0);
        
        // Reset da câmera para posição inicial
        if (camera && controls) {
            camera.position.set(0, 0, 5);
            controls.target.set(0, 0, 0);
            controls.update();
        }
        
        showStatus('Avatar resetado.');
        console.log('Avatar resetado para posição inicial.');
    };

    document.addEventListener('DOMContentLoaded', () => {
        showLoading('Preparando a cena 3D...');
        setTimeout(tryLoadGLB, 100);
    });
}
