import { Text, View, StyleSheet } from "react-native";
import { Redirect, Stack } from "expo-router";

import { useSession } from "@/components/auth/ctx";
import DockBar from "@/components/common/DockBar";

export default function AppLayout() {
  const { session, isLoading } = useSession();

  // Show loading indicator
  if (isLoading) {
    return <Text>Loading...</Text>;
  }

  // Redirect to sign-in page if not authenticated
  if (!session) {
    return <Redirect href="/sign-in" />;
  }

  // Render authenticated stack with DockBar
  return (
    <View style={styles.container}>
      {/* <Stack /> */}
      <DockBar />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  stack: {
    flex: 1,
  },
});
