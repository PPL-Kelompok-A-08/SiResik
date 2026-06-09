export type UserRole = 'resident' | 'admin' | 'officer';

export type AppView = 'landing' | 'dashboard' | 'pickup' | 'points' | 'education' | 'admin-panel' | 'officer-panel';

export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
  points: number;
}

export interface WasteCategory {
  id: string;
  name: string;
  pointsPerKg: number;
  icon: string;
  color: string;
}

export interface PickupRequest {
  id: string;
  residentId: string;
  residentName: string;
  category: string;
  estimatedWeight: number;
  calculatedPoints: number;
  status: 'pending' | 'assigned' | 'completed';
  officerId?: string;
  date: string;
}

export const WASTE_CATEGORIES: WasteCategory[] = [
  { id: 'pet-plastic', name: 'Plastik PET', pointsPerKg: 1500, icon: '📦', color: 'text-blue-500' },
  { id: 'paper', name: 'Kertas & Karton', pointsPerKg: 1000, icon: '📄', color: 'text-amber-500' },
  { id: 'aluminum', name: 'Aluminium / Kaleng', pointsPerKg: 3000, icon: '🥫', color: 'text-purple-500' },
  { id: 'organic', name: 'Organik', pointsPerKg: 500, icon: '🌱', color: 'text-emerald-500' },
  { id: 'b3-electronic', name: 'B3 / Elektronik', pointsPerKg: 5000, icon: '⚡', color: 'text-red-500' },
];