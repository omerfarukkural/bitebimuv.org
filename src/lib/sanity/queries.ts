import { groq } from 'next-sanity'

export const EVENTS_QUERY = groq`
*[_type == "event" && isActive == true] | order(eventDate asc) {
  _id, title, slug, category, shortDescription,
  "image": image.asset->url,
  eventDate, endDate, location, locationUrl,
  maxParticipants, isRegistrationOpen, requiresApproval, isFeatured, tags
}`

export const UPCOMING_EVENTS_QUERY = groq`
*[_type == "event" && isActive == true && eventDate > now()] | order(eventDate asc)[0...6] {
  _id, title, slug, category, shortDescription,
  "image": image.asset->url,
  eventDate, endDate, location, maxParticipants, isRegistrationOpen, isFeatured
}`

export const EVENT_BY_SLUG_QUERY = groq`
*[_type == "event" && slug.current == $slug][0] {
  _id, title, slug, category, description, shortDescription,
  "image": image.asset->url,
  eventDate, endDate, location, locationUrl,
  maxParticipants, isRegistrationOpen, requiresApproval, isFeatured, tags,
  volunteerNeeded, ageGroup
}`

export const FEATURED_EVENTS_QUERY = groq`
*[_type == "event" && isActive == true && isFeatured == true] | order(eventDate asc)[0...3] {
  _id, title, slug, category, shortDescription,
  "image": image.asset->url,
  eventDate, location, isRegistrationOpen
}`

export const POSTS_QUERY = groq`
*[_type == "post"] | order(publishedAt desc) {
  _id, title, slug, category, excerpt,
  "coverImage": coverImage.asset->url,
  publishedAt, isFeatured, tags
}`

export const PROJECTS_QUERY = groq`
*[_type == "project"] | order(_createdAt desc) {
  _id, title, slug, status, shortDescription,
  "coverImage": coverImage.asset->url,
  targetAmount, currentAmount, beneficiaryCount, location, isFeatured
}`

export const TEAM_QUERY = groq`
*[_type == "teamMember" && isActive == true] | order(order asc) {
  _id, name, role, bio, linkedin,
  "photo": photo.asset->url
}`

export const STORIES_QUERY = groq`
*[_type == "story" && hasParentConsent == true] | order(_createdAt desc) {
  _id, childName, age, shortQuote,
  "photo": photo.asset->url,
  isFeatured, date
}`

export const SITE_SETTINGS_QUERY = groq`
*[_type == "siteSettings"][0] {
  siteName, siteDescription,
  "logo": logo.asset->url,
  socialLinks, contactEmail, contactPhone, address,
  impactStats, smileCounter
}`

export const ANNOUNCEMENTS_QUERY = groq`
*[_type == "announcement" && isActive == true && (expiresAt > now() || !defined(expiresAt))] | order(_createdAt desc) {
  _id, title, content, type
}`
