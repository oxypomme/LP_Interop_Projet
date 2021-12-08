<?xml version='1.0' encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements="*" />

  <xsl:template match="/">
    <div class="meteo--list">
      <xsl:apply-templates select="previsions/echeance" />
    </div>
  </xsl:template>

  <!-- No useless data -->
  <xsl:template match="echeance"></xsl:template>
  <!-- Render HTML element -->
  <xsl:template match="echeance" mode="render">
    <div class="meteo--item">
      <!-- Température -->
      <div class="meteo--item_temperature">
        <xsl:value-of select="round(temperature/level[@val='2m'] - 273.15)" />
      </div>
      <!-- Status -->
      <div class="meteo--item_status">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <i class="fas cloud-rain"></i>
          </xsl:when>
          <xsl:otherwise>
            <i class="fas fa-sun"></i>
          </xsl:otherwise>
        </xsl:choose>
      </div>
      <!-- Humidité -->
      <div class="meteo--item_humid">
        <xsl:value-of select="humidite/level" />
        %
      </div>
      <!-- Neige (debug) -->
      <xsl:choose>
        <xsl:when test="risque_neige = 'non'">
          <div class="meteo--item_neige">
            <i class="fas fa-snowflake"></i>
          </div>
        </xsl:when>
      </xsl:choose>
      <!-- Neige -->
      <xsl:choose>
        <xsl:when test="risque_neige = 'oui'">
          <div class="meteo--item_neige">
            <i class="fas fa-snowflake"></i>
          </div>
        </xsl:when>
      </xsl:choose>
    </div>
  </xsl:template>
  <!-- Various echances -->
  <xsl:template match="echeance[@hour=3]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=6]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=9]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=15]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
</xsl:stylesheet>