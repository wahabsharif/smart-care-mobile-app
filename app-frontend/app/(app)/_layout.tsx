import React, { useEffect, useState } from "react";
import { Text, View, StyleSheet, ActivityIndicator } from "react-native"; // Import ActivityIndicator
import { Redirect } from "expo-router"; // Use Redirect component from expo-router
import AsyncStorage from "@react-native-async-storage/async-storage";
import { useSession } from "@/components/auth/ctx";
import DockBar from "@/components/common/DockBar";

export default function AppLayout() {
  const [isTokenAvailable, setIsTokenAvailable] = useState<boolean | null>(
    null
  ); // Store token availability state
  const { isLoading } = useSession();

  useEffect(() => {
    const checkToken = async () => {
      const token = await AsyncStorage.getItem("@token"); // Retrieve token from AsyncStorage
      setIsTokenAvailable(!!token); // If token exists, set to true, else false
    };

    checkToken(); // Check token on mount
  }, []);

  // Show spinner until the token availability check is complete
  if (isLoading || isTokenAvailable === null) {
    return (
      <View style={styles.loaderContainer}>
        <Text>
          <ActivityIndicator size="large" color="#0000ff" />
        </Text>
      </View>
    );
  }

  // If token is not available, redirect to the sign-in page
  if (!isTokenAvailable) {
    return <Redirect href="/sign-in" />;
  }

  // If token is available, render the authenticated layout with DockBar
  return (
    <View style={styles.container}>
      <DockBar />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  loaderContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
});
