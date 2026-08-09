import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class MyTokenScreen extends StatefulWidget {
  final UserModel user;
  final bool hideAppBar;

  const MyTokenScreen({super.key, required this.user, this.hideAppBar = false});

  @override
  State<MyTokenScreen> createState() => _MyTokenScreenState();
}

class _MyTokenScreenState extends State<MyTokenScreen>
    with SingleTickerProviderStateMixin {
  late Future<Map<String, dynamic>?> _tokenFuture;
  late AnimationController _pulseController;
  late Animation<double> _pulseAnimation;

  static const Color _primary = Color(0xFFFF0088);

  @override
  void initState() {
    super.initState();

    // Pulse animation for the active token badge
    _pulseController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1200),
    )..repeat(reverse: true);
    _pulseAnimation =
        Tween<double>(begin: 0.95, end: 1.05).animate(CurvedAnimation(
      parent: _pulseController,
      curve: Curves.easeInOut,
    ));

    _load();
  }

  @override
  void dispose() {
    _pulseController.dispose();
    super.dispose();
  }

  void _load() {
    setState(() {
      _tokenFuture = _fetchTodayToken();
    });
  }

  /// Fetches the user's bookings and returns the first active/pending booking
  /// for today (or tomorrow, depending on how your backend creates tokens).
  Future<Map<String, dynamic>?> _fetchTodayToken() async {
    final bookings = await ApiService.getUserBookings(widget.user.id);
    if (bookings.isEmpty) return null;

    // Prefer active > pending, then most recent by id
    bookings.sort((a, b) {
      const order = {'active': 0, 'pending': 1, 'completed': 2, 'cancelled': 3};
      final ao = order[a['status']] ?? 99;
      final bo = order[b['status']] ?? 99;
      if (ao != bo) return ao.compareTo(bo);
      return (b['id'] as int).compareTo(a['id'] as int);
    });

    return bookings.first as Map<String, dynamic>;
  }

  @override
  Widget build(BuildContext context) {
    final content = FutureBuilder<Map<String, dynamic>?>(
      future: _tokenFuture,
      builder: (context, snap) {
        if (snap.connectionState == ConnectionState.waiting) {
          return const Center(
              child: CircularProgressIndicator(color: _primary));
        }
        if (snap.hasError || snap.data == null) {
          return _buildNoToken();
        }
        return _buildTokenView(snap.data!);
      },
    );

    if (widget.hideAppBar) {
      return Container(color: const Color(0xFFF7F8FC), child: content);
    }
    return Scaffold(
      backgroundColor: const Color(0xFFF7F8FC),
      appBar: AppBar(
        title: const Text('My Token'),
        centerTitle: true,
        backgroundColor: _primary,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      body: content,
    );
  }

  // ────────────────────────────────────────────────────────────
  //  NO TOKEN STATE
  // ────────────────────────────────────────────────────────────
  Widget _buildNoToken() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 110,
              height: 110,
              decoration: BoxDecoration(
                color: _primary.withValues(alpha: 0.08),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.confirmation_number_outlined,
                  size: 56, color: _primary),
            ),
            const SizedBox(height: 28),
            const Text(
              'No Active Token',
              style: TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.w800,
                color: Color(0xFF1A1A2E),
              ),
            ),
            const SizedBox(height: 10),
            const Text(
              'You don\'t have any active or upcoming\ntoken for today.',
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 14, color: Colors.grey, height: 1.6),
            ),
            const SizedBox(height: 32),
            ElevatedButton.icon(
              onPressed: _load,
              icon: const Icon(Icons.refresh, size: 18),
              label: const Text('Refresh'),
              style: ElevatedButton.styleFrom(
                backgroundColor: _primary,
                foregroundColor: Colors.white,
                padding:
                    const EdgeInsets.symmetric(horizontal: 32, vertical: 14),
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12)),
                textStyle: const TextStyle(
                    fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ────────────────────────────────────────────────────────────
  //  TOKEN CARD VIEW
  // ────────────────────────────────────────────────────────────
  Widget _buildTokenView(Map<String, dynamic> booking) {
    final status = (booking['status'] as String? ?? '').toLowerCase();
    final isActive = status == 'active';
    final isPending = status == 'pending';
    final isCompleted = status == 'completed';

    final statusColor = isActive
        ? const Color(0xFF0077FF)
        : isPending
            ? const Color(0xFFF59E0B)
            : isCompleted
                ? const Color(0xFF10B981)
                : Colors.red;

    final statusLabel = status.toUpperCase();
    final tokenNo = '${booking['token_number'] ?? '--'}';
    final unitName =
        (booking['unit']?['name'] as String? ?? 'Department').toUpperCase();
    final slotTime = booking['slot_time'] as String? ?? '--:--';
    final bookingDate = booking['booking_date'] as String? ?? '';
    final type = (booking['type'] as String? ?? '').toUpperCase();

    return RefreshIndicator(
      color: _primary,
      onRefresh: () async => _load(),
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.fromLTRB(20, 28, 20, 32),
        child: Column(
          children: [
            // ── PATIENT GREETING ──
            _buildGreetingRow(statusColor, statusLabel, isActive),

            const SizedBox(height: 24),

            // ── MAIN TOKEN TICKET ──
            _buildTokenTicket(
              tokenNo: tokenNo,
              unitName: unitName,
              slotTime: slotTime,
              bookingDate: bookingDate,
              type: type,
              isActive: isActive,
              isPending: isPending,
              statusColor: statusColor,
            ),

            const SizedBox(height: 20),

            // ── DETAILS CARD ──
            _buildDetailsCard(booking, statusColor),

            const SizedBox(height: 20),

            // ── COPY / CANCEL ACTIONS ──
            _buildActions(booking, isActive, tokenNo),

            const SizedBox(height: 10),

            // ── TIP ──
            if (isActive || isPending)
              _buildTip(isActive),
          ],
        ),
      ),
    );
  }

  Widget _buildGreetingRow(
      Color statusColor, String statusLabel, bool isActive) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Hello, ${widget.user.name.split(' ').first} 👋',
                style: const TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.w800,
                  color: Color(0xFF1A1A2E),
                ),
              ),
              const SizedBox(height: 4),
              const Text(
                'Here is your token for today',
                style: TextStyle(fontSize: 13, color: Colors.grey),
              ),
            ],
          ),
        ),
        // Live status badge
        AnimatedBuilder(
          animation: _pulseAnimation,
          builder: (context, child) => Transform.scale(
            scale: isActive ? _pulseAnimation.value : 1.0,
            child: child,
          ),
          child: Container(
            padding:
                const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: statusColor.withValues(alpha: 0.15),
              borderRadius: BorderRadius.circular(50),
              border: Border.all(color: statusColor.withValues(alpha: 0.4)),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                if (isActive)
                  Container(
                    width: 8,
                    height: 8,
                    margin: const EdgeInsets.only(right: 6),
                    decoration: BoxDecoration(
                        color: statusColor, shape: BoxShape.circle),
                  ),
                Text(
                  statusLabel,
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                    color: statusColor,
                    letterSpacing: 0.6,
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildTokenTicket({
    required String tokenNo,
    required String unitName,
    required String slotTime,
    required String bookingDate,
    required String type,
    required bool isActive,
    required bool isPending,
    required Color statusColor,
    }) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: _primary.withValues(alpha: 0.18),
            blurRadius: 28,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(24),
        child: Stack(
          children: [
            // Background gradient
            Container(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFFFF0088), Color(0xFFFF6EC7)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
            ),
            // Decorative circles
            Positioned(
              right: -40,
              top: -40,
              child: Container(
                width: 160,
                height: 160,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Colors.white.withValues(alpha: 0.08),
                ),
              ),
            ),
            Positioned(
              left: -20,
              bottom: -30,
              child: Container(
                width: 120,
                height: 120,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Colors.white.withValues(alpha: 0.06),
                ),
              ),
            ),
            // Content
            Padding(
              padding: const EdgeInsets.all(28),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Hospital name + icon
                  Row(
                    children: [
                      const Icon(Icons.local_hospital,
                          color: Colors.white70, size: 16),
                      const SizedBox(width: 6),
                      const Expanded(
                        child: Text(
                          'GMCCH THRISSUR',
                          style: TextStyle(
                            color: Colors.white70,
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            letterSpacing: 1.2,
                          ),
                        ),
                      ),
                      // Type chip
                      if (type.isNotEmpty)
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 10, vertical: 3),
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.2),
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: Text(
                            type,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              letterSpacing: 0.5,
                            ),
                          ),
                        ),
                    ],
                  ),

                  const SizedBox(height: 8),

                  // Unit name
                  Text(
                    unitName,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                      letterSpacing: 0.3,
                    ),
                  ),

                  const SizedBox(height: 18),

                  // Dashed divider
                  Row(
                    children: List.generate(
                      40,
                      (i) => Expanded(
                        child: Container(
                          height: 1,
                          color: i.isEven
                              ? Colors.white.withValues(alpha: 0.4)
                              : Colors.transparent,
                        ),
                      ),
                    ),
                  ),

                  const SizedBox(height: 20),

                  // Token number — the hero
                  Center(
                    child: Column(
                      children: [
                        const Text(
                          'TOKEN NUMBER',
                          style: TextStyle(
                            color: Colors.white70,
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            letterSpacing: 2,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          tokenNo,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 80,
                            fontWeight: FontWeight.w900,
                            height: 1,
                            letterSpacing: -2,
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  // Dashed divider
                  Row(
                    children: List.generate(
                      40,
                      (i) => Expanded(
                        child: Container(
                          height: 1,
                          color: i.isEven
                              ? Colors.white.withValues(alpha: 0.4)
                              : Colors.transparent,
                        ),
                      ),
                    ),
                  ),

                  const SizedBox(height: 18),

                  // Slot time + date row
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _ticketField('REPORTING TIME', slotTime),
                      _ticketField('DATE', bookingDate, alignRight: true),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _ticketField(String label, String value, {bool alignRight = false}) {
    return Column(
      crossAxisAlignment:
          alignRight ? CrossAxisAlignment.end : CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            color: Colors.white60,
            fontSize: 10,
            fontWeight: FontWeight.w600,
            letterSpacing: 1,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          value,
          style: const TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }

  Widget _buildDetailsCard(Map<String, dynamic> booking, Color statusColor) {
    final patientName = widget.user.name;
    final crno = widget.user.crno ?? '--';
    final age = widget.user.userAge != null ? '${widget.user.userAge} yrs' : '--';
    final gender = widget.user.userGender ?? '--';

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Patient Details',
            style: TextStyle(
              fontWeight: FontWeight.w800,
              fontSize: 15,
              color: Color(0xFF1A1A2E),
            ),
          ),
          const SizedBox(height: 14),
          _detailRow(Icons.person_outline, 'Name', patientName),
          const Divider(height: 20),
          _detailRow(Icons.badge_outlined, 'CR Number', crno),
          const Divider(height: 20),
          _detailRow(Icons.cake_outlined, 'Age', age),
          const Divider(height: 20),
          _detailRow(Icons.wc_outlined, 'Gender', gender),
        ],
      ),
    );
  }

  Widget _detailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 18, color: _primary),
        const SizedBox(width: 12),
        Text(
          label,
          style: const TextStyle(
            fontSize: 13,
            color: Colors.grey,
            fontWeight: FontWeight.w500,
          ),
        ),
        const Spacer(),
        Text(
          value,
          style: const TextStyle(
            fontSize: 13,
            fontWeight: FontWeight.w700,
            color: Color(0xFF1A1A2E),
          ),
        ),
      ],
    );
  }

  Widget _buildActions(
      Map<String, dynamic> booking, bool isActive, String tokenNo) {
    return Row(
      children: [
        // Copy token number
        Expanded(
          child: OutlinedButton.icon(
            onPressed: () {
              Clipboard.setData(ClipboardData(text: tokenNo));
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: const Text('Token number copied!'),
                  backgroundColor: _primary,
                  behavior: SnackBarBehavior.floating,
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10)),
                ),
              );
            },
            icon: const Icon(Icons.copy, size: 16),
            label: const Text('Copy Token'),
            style: OutlinedButton.styleFrom(
              foregroundColor: _primary,
              side: const BorderSide(color: _primary),
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12)),
              padding: const EdgeInsets.symmetric(vertical: 13),
              textStyle:
                  const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
            ),
          ),
        ),

        if (isActive) ...[
          const SizedBox(width: 12),
          // Cancel booking
          Expanded(
            child: OutlinedButton.icon(
              onPressed: () => _handleCancel(booking['id']),
              icon: const Icon(Icons.cancel_outlined, size: 16),
              label: const Text('Cancel'),
              style: OutlinedButton.styleFrom(
                foregroundColor: Colors.red,
                side: const BorderSide(color: Colors.red),
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12)),
                padding: const EdgeInsets.symmetric(vertical: 13),
                textStyle:
                    const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
              ),
            ),
          ),
        ],
      ],
    );
  }

  Widget _buildTip(bool isActive) {
    return Container(
      margin: const EdgeInsets.only(top: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFF0F9FF),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFBAE6FD)),
      ),
      child: Row(
        children: [
          const Icon(Icons.info_outline, size: 18, color: Color(0xFF0369A1)),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              isActive
                  ? 'Please arrive at least 15 minutes before your reporting time.'
                  : 'Your token is pending approval. You will be notified once approved.',
              style: const TextStyle(
                fontSize: 12,
                color: Color(0xFF0369A1),
                fontWeight: FontWeight.w500,
                height: 1.5,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _handleCancel(int bookingId) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape:
            RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Cancel Booking?'),
        content:
            const Text('Are you sure you want to cancel this token?'),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(context, false),
              child: const Text('NO')),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child:
                const Text('YES, CANCEL', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      final result = await ApiService.cancelBooking(bookingId);
      if (!mounted) return;
      if (result['status'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text('Booking cancelled successfully'),
            backgroundColor: Colors.green,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
        _load();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content:
                Text(result['message'] ?? 'Cancellation failed'),
            backgroundColor: Colors.red,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    }
  }
}
