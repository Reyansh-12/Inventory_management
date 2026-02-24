import { Canvas, useFrame } from '@react-three/fiber';
import { Float, MeshDistortMaterial } from '@react-three/drei';
import { useRef } from 'react';

function Bubble() {
  return (
    <Float speed={4} rotationIntensity={1} floatIntensity={2}>
      <mesh position={[2, 0, 0]}>
        <sphereGeometry args={[1, 64, 64]} />
        <MeshDistortMaterial color="#e0f2fe" speed={3} distort={0.4} transparent opacity={0.3} />
      </mesh>
    </Float>
  );
}

export default function SectionBackground() {
  return (
    <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', zIndex: -1, pointerEvents: 'none' }}>
      <Canvas>
        <ambientLight intensity={0.5} />
        <Bubble />
      </Canvas>
    </div>
  );
}