import { useEffect, useCallback, useReducer } from "react";
import * as SecureStore from "expo-secure-store";
import { Platform } from "react-native";

type AsyncState<T> = [boolean, T | null];
type UseStateHook<T> = [AsyncState<T>, (value: T | null) => void];

function useAsyncState<T>(
  initialValue: AsyncState<T> = [true, null]
): UseStateHook<T> {
  return useReducer(
    (state: AsyncState<T>, action: T | null): AsyncState<T> => [false, action],
    initialValue
  ) as UseStateHook<T>;
}

export async function setStorageItemAsync(key: string, value: string | null) {
  try {
    if (Platform.OS === "web") {
      if (value === null) {
        localStorage.removeItem(key);
      } else {
        localStorage.setItem(key, value);
      }
    } else {
      if (value === null) {
        await SecureStore.deleteItemAsync(key);
      } else {
        await SecureStore.setItemAsync(key, value);
      }
    }
  } catch (error) {
    console.error(`Error setting storage item for key "${key}":`, error);
  }
}

export function useStorageState(key: string): UseStateHook<string> {
  const [state, setState] = useAsyncState<string>();

  useEffect(() => {
    const fetchValue = async () => {
      try {
        if (Platform.OS === "web") {
          const value = localStorage.getItem(key) || null;
          setState(value);
        } else {
          const value = await SecureStore.getItemAsync(key);
          setState(value);
        }
      } catch (error) {
        console.error(`Error fetching storage item for key "${key}":`, error);
      }
    };

    fetchValue();
  }, [key]);

  const setValue = useCallback(
    (value: string | null) => {
      setState(value);
      setStorageItemAsync(key, value);
    },
    [key]
  );

  return [state, setValue];
}
