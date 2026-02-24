import { Canvas } from '@react-three/fiber';
import { Stars } from '@react-three/drei';

const ThreeBackground = () => {
  return (
    <div style={{ 
      position: 'absolute', 
      top: 0, 
      left: 0, 
      width: '100%', 
      height: '100%', 
      zIndex: -1,
      background: 'linear-gradient(to bottom, #f8f9fa, #e9ecef)' 
    }}>
      <Canvas>
        <Stars radius={100} depth={50} count={5000} factor={4} saturation={0} fade speed={1} />
      </Canvas>
    </div>
  );
};

export default ThreeBackground;