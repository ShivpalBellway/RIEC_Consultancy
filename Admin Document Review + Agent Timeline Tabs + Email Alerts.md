# Implementation Plan: Phase 1 & 2 — Admin Document Review + Agent Timeline Tabs + Email Alerts

## Overview

Teen kaam karne hain:
1. **Agent Side** → Application Timeline ko interactive **tabs** me convert karna
2. **Admin Side** → Document **Verify / Reject** panel + enhanced student detail page  
3. **Email to Agent** → Jab Admin koi action le, Agent ki apni email par professional email jaaye

---

## Part 1: Agent Student Show Page — Timeline as Tabs

### Kya Banega

`/agent/students/{id}` page par jo Application Timeline hai abhi static display hai. Isse **clickable tabs** me convert karenge jahan har tab apna content show kare.

**Tab Structure:**
| Tab | Content |
|---|---|
| 📄 **Submitted** | Student ka basic profile, Personal info, Passport details |
| 🔍 **Under Review** | Document upload checklist + progress bar |
| 🏛️ **University Assigned** | Assigned university name, Korean address details |
| ✉️ **Offer Letter** | Offer Letter document status + upload section |
| 🛂 **Visa Phase** | Visa related documents, Status notes from admin |
| ✅ **Completed** | Final enrolled status, Completion summary |

**Behavior:**
- Active tab = student ka current status wala tab (highlighted in gold/primary color)
- Completed phases = green checkmark
- Future phases = grayed out
- Agent can click any tab to view content for that phase

---

## Part 2: Admin Student Show Page — Document Verify/Reject

### Kya Banega

Admin ke `/admin/students/{id}` page par abhi documents table sirf status dikhata hai. **Verify** aur **Reject** buttons add karenge.

**Naya Layout:**

#### Document Table me naye columns/actions:
- **Download** button (already hai)
- **Verify** button → Document status `verified` ho jaata hai → Agent ko email
- **Reject** button → Popup khulega → Admin comment daal ke reject kare → Agent ko email

**Admin Student Show Page full redesign:**
- Header: Student name + Agent info + Current status badge
- **Tab 1: Overview** → Personal details, Korean address, University assignment form
- **Tab 2: Documents** → Full mandatory checklist with per-document verify/reject
- **Tab 3: Status Timeline** → Admin status update form + visual timeline

---

## Part 3: Email to Agent (Agent's Own Email)

### Triggers & Email Templates

Jab bhi Admin koi action le, **Agent ki registered email** par email jaaye:

| Action | Email Subject |
|---|---|
| Student status changed | `📋 Application Status Update – [Student Name]` |
| Document Verified | `✅ Document Verified – [Document Type] – [Student Name]` |
| Document Rejected | `❌ Document Review Required – [Student Type] – [Student Name]` |
| University Assigned | `🏛️ University Assigned – [Student Name]` |

**Email Design:** Professional HTML email with RIEC branding (Primary color `#1a2f5e`, Gold `#dca737`), clear message, and link to view in portal.

---

## Files to Create / Modify

### New Files
- `app/Mail/AgentStudentUpdateMail.php` — New Mailable for Agent email alerts
- `resources/views/emails/agent_student_update.blade.php` — HTML email template

### Modify Files
- `app/Http/Controllers/Admin/StudentController.php`
  - `updateStatus()` → Add send email to agent's email
  - `updateUniversity()` → Add send email to agent's email
  - New method: `verifyDocument($document)` → Mark verified + email agent
  - New method: `rejectDocument($document)` → Mark rejected + save comment + email agent
- `routes/web.php` → Add 2 new admin document routes (verify + reject)
- `resources/views/pages/admin/students/show.blade.php` → Full redesign with tabs + verify/reject buttons
- `resources/views/pages/agent/students/show.blade.php` → Timeline to tabs conversion

---

## Verification Plan

- Admin document verify kare → `student_documents.status = 'verified'` DB me check
- Admin document reject kare → `status = 'rejected'`, `admin_comment` saved check
- Email log check karein (`storage/logs/laravel.log`) ya Mailtrap se verify
- Agent show page par tabs correctly active/completed/upcoming show ho

---

## Open Questions

> [!IMPORTANT]
> **Q1: Agent email par email jaani chahiye ya Agent ka email form SiteSetting se lena chahiye?**
> — Mera assumption: **Agent ki khud ki registered email** (`agent->email`) par jaayegi, SiteSetting wali email Admin alert ke liye thi.

> [!IMPORTANT]  
> **Q2: Kya Agent ke pass rejected document pe re-upload option chahiye automatically?**
> — Plan me iska answer chahiye taaki agent show page tabs me sahi information dikhayein.

> [!NOTE]
> **Q3: Tabs me kya agent sirf apne current phase ka content dekh sakta hai ya saare tabs clickable hain?**
> — Plan: Saare tabs clickable honge, current phase highlighted hoga.
