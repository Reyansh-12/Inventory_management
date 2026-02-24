import { Canvas, useFrame } from "@react-three/fiber";
import { MeshDistortMaterial, Sphere, Float } from "@react-three/drei";
import { useRef } from "react";

function AnimatedShape() {
  const mesh = useRef();
  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    mesh.current.rotation.x = Math.sin(time / 4);
    mesh.current.rotation.y = Math.cos(time / 4);
  });

  return (
    <Float speed={5} rotationIntensity={2} floatIntensity={2}>
      <Sphere ref={mesh} args={[1, 100, 200]} scale={2.4}>
        <MeshDistortMaterial
          color="#f3f4f6"
          speed={4}
          distort={0.3}
          radius={1}
        />
      </Sphere>
    </Float>
  );
}

export default function ThreeCanvas() {
  return (
    <div className="three-bg">
      <Canvas camera={{ position: [0, 0, 5] }}>
        <ambientLight intensity={1.5} />
        <pointLight position={[10, 10, 10]} />
        <AnimatedShape />
      </Canvas>
    </div>
  );
}