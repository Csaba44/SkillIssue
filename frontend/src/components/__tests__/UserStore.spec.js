import { setActivePinia, createPinia } from 'pinia';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useUserStore } from '../../stores/UserStore';
import api from '../../config/api';
import { socket } from '../../config/websocket';

vi.mock('../../config/api', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
  },
}));

vi.mock('../../config/websocket', () => ({
  socket: {
    connected: false,
    connect: vi.fn(),
    disconnect: vi.fn(),
  },
}));

describe('UserStore', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
    vi.clearAllMocks();
  });

  it('1. Sikeres verifySession esetén beállítja a felhasználót', async () => {
    const userStore = useUserStore();
    const mockUser = { id: 1, name: 'Teszt Elek' };
    
    api.get.mockResolvedValue({ data: mockUser });

    await userStore.verifySession();

    expect(userStore.user).toEqual(mockUser);
    expect(userStore.isAuthenticated).toBe(true);
    expect(socket.connect).toHaveBeenCalled();
  });

  it('2. 401 hiba esetén kilépteti a felhasználót', async () => {
    const userStore = useUserStore();
    
    api.get.mockRejectedValue({
      response: { status: 401 }
    });

    await userStore.verifySession();

    expect(userStore.user).toBe(false);
    expect(userStore.isAuthenticated).toBe(false);
  });

  it('3. Logout törli az adatokat és disconnecteli a socketet', async () => {
    const userStore = useUserStore();
    api.get.mockResolvedValue({});
    api.post.mockResolvedValue({});
    
    userStore.user = { name: 'Teszt' };
    userStore.isAuthenticated = true;

    await userStore.logout();

    expect(api.post).toHaveBeenCalledWith('/api/logout');
    expect(socket.disconnect).toHaveBeenCalled();
    expect(api.get).toHaveBeenCalledWith('/api/users'); 
  });
});